# P2 Implementation Guide: Specialized Features
## Maintenance Module, Activity Logs, & QR Printing

**Date**: 2026-01-15  
**Status**: ✅ Ready for Testing  
**Priority**: P2 (Specialized Features)

---

## 🔧 Feature 1: Maintenance & Calibration Module

### What Was Implemented:

**Files Created:**
1. `database/migrations/2026_01_15_120047_create_maintenances_table.php` - Database schema
2. `app/Models/Maintenance.php` - Model with relationships and scopes
3. `app/Http/Controllers/MaintenanceController.php` - CRUD & workflow logic

**Files Modified:**
1. `app/Models/Item.php` - Added `maintenances()` relationship
2. `routes/web.php` - Added maintenance routes

**Database Schema:**
```sql
maintenances table:
- id
- item_id (FK to items)
- type (calibration|repair|cleaning|inspection)
- scheduled_date
- completed_date (nullable)
- cost (integer, nullable)
- status (pending|in_progress|completed)
- technician_name
- notes
- timestamps
- soft_deletes
```

**Key Features:**

✅ **Automatic Item Status Management**
- When maintenance `status` → `in_progress`, Item `status` → `maintenance` (prevents borrowing)
- When maintenance `status` → `completed`, Item `status` → `available` (allows borrowing again)

✅ **3 States of Maintenance**
1. **Pending**: Scheduled but not started
2. **In Progress**: Currently being serviced (item unavailable)
3. **Completed**: Service finished (item available)

✅ **Maintenance Types**
- **Calibration**: Equipment calibration
- **Repair**: Fixing damaged equipment
- **Cleaning**: Deep cleaning/sterilization
- **Inspection**: Regular inspection/checkup

### Routes Available:

```php
GET  /maintenances                       → List all maintenances
GET  /maintenances/create?item_id={id}   → Create form
POST /maintenances                       → Store new maintenance
GET  /maintenances/{id}/edit             → Edit form
PUT  /maintenances/{id}                  → Update maintenance
DELETE /maintenances/{id}                → Delete maintenance

POST /maintenances/{id}/start            → Mark as in_progress (locks item)
POST /maintenances/{id}/complete         → Mark as completed (unlocks item)
```

### Usage Example:

**Scenario**: Schedule a calibration for Mikroskop

1. Go to Item Detail Page → "Riwayat Perawatan" section
2. Click "Tambah Jadwal Maintenance"
3. Fill form:
   - Type: Calibration
   - Scheduled Date: 2026-02-01
   - Technician: PT. BioMedical Services
   - Notes: Annual calibration
4. Click Save → Status: `pending`

**When technician arrives:**
1. Click "Start" button → Status: `in_progress`
2. Item status automatically changes to `maintenance`
3. Students cannot borrow this item

**After service:**
1. Click "Complete" button
2. Fill cost (optional): Rp 500,000
3. Add completion notes (optional)
4. Item status automatically restores to `available`

### Model Helper Methods:

```php
// Get all pending maintenances
$pending = Maintenance::pending()->get();

// Get overdue maintenances
$overdue = Maintenance::overdue()->get();

// Get maintenances for specific item
$maintenances = $item->maintenances;

// Get formatted labels
$maintenance->type_label;    // "Kalibrasi"
$maintenance->status_label;  // "Dalam Proses"
```

---

## 📊 Feature 2: Activity Logs (Audit Trail)

### Setup Required:

**Step 1: Install Spatie Activity Log**
```bash
composer require spatie/laravel-activitylog
```

**Step 2: Publish & Migrate**
```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan migrate
```

This creates the `activity_log` table.

### What Was Configured:

**Files Modified:**
1. `app/Models/Item.php` - Added `LogsActivity` trait and config

**Activity Log Configuration:**
```php
// In Item model
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['*'])           // Log all attributes
        ->logOnlyDirty()           // Only log changed values
        ->dontSubmitEmptyLogs()     // Skip if nothing changed
        ->setDescriptionForEvent(fn($e) => "Item was {$e}");
}
```

### What Gets Logged Automatically:

✅ **Item Created**
```
User: Admin John
Event: created
Subject: Item #123 (Mikroskop)
Properties: { name: "Mikroskop", serial_number: "...", ... }
```

✅ **Item Updated**
```
User: Admin John
Event: updated
Subject: Item #123 (Mikroskop)
Properties: 
  old: { status: "available", quantity: 10 }
  attributes: { status: "maintenance", quantity: 9 }
```

✅ **Item Deleted**
```
User: Admin John
Event: deleted
Subject: Item #123 (Mikroskop)
```

### Viewing Activity Logs:

**Option 1: Database Query**
```sql
SELECT * FROM activity_log 
ORDER BY created_at DESC 
LIMIT 50;
```

**Option 2: Laravel Tinker**
```bash
php artisan tinker
```
```php
// Get all activities
Activity::all();

// Get activities for specific item
Activity::forSubject($item)->get();

// Get activities by specific user
Activity::causedBy(auth()->user())->get();

// Get recent changes
Activity::latest()->take(10)->get();
```

**Option 3: Create Admin UI** (Recommended for production)

Create `app/Http/Controllers/ActivityLogController.php`:
```php
use Spatie\Activitylog\Models\Activity;

public function index()
{
    $activities = Activity::with(['subject', 'causer'])
        ->latest()
        ->paginate(50);
        
    return view('admin.activity_logs', compact('activities'));
}
```

View template:
```blade
@foreach($activities as $activity)
    <tr>
        <td>{{ $activity->causer->name ?? 'System' }}</td>
        <td>{{ $activity->description }}</td>
        <td>{{ $activity->subject_type }}</td>
        <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
    </tr>
@endforeach
```

### Manual Logging (Advanced):

```php
// Log custom activity
activity()
   ->performedOn($item)
   ->causedBy(auth()->user())
   ->withProperties(['old_stock' => 10, 'new_stock' => 9])
   ->log('Stock adjusted manually');

// Log without model
activity()
   ->causedBy(auth()->user())
   ->log('User exported data to Excel');
```

---

## 🏷️ Feature 3: Bulk QR Label Printing

### What Was Implemented:

**Files Created:**
1. `resources/views/pdf/qr_labels.blade.php` - PDF template (3x5 grid)

**Files Modified:**
1. `app/Http/Controllers/ItemController.php` - Added `printBulkQr()` method
2. `routes/web.php` - Added print route

### How It Works:

**Step 1: Select Items**

On Items Index page (`/items`):
- Add checkboxes to each item row
- Select multiple items
- Click "Cetak Label QR Terpilih" button

**Step 2: Generate PDF**

The system will:
1. Fetch selected items from database
2. Generate 3x5 grid layout (15 labels per page)
3. For each label, include:
   - Lab header
   - QR Code (from storage or generated inline)
   - Item name  
   - Serial number
   - Asset number
   - Room location
4. Auto-paginate if more than 15 items

**Step 3: Print**

- PDF downloads automatically
- Filename: `QR_Labels_20260115_143022.pdf`
- Print on A4 paper
- Cut and paste labels on equipment

### Label Layout:

```
┌────────────────────────┐
│ LAB BIOMEDIS STEI ITB  │
│                        │
│     ┌───────────┐      │
│     │  QR CODE  │      │
│     │           │      │
│     └───────────┘      │
│                        │
│   Mikroskop Olympus    │
│   SN: E-MIKR-26001     │
│   Asset: BM-LAB-001    │
│   📍 R. Instrumentasi  │
│                        │
│ Inventaris Lab 2026    │
└────────────────────────┘
```

### Frontend Implementation (Items Index):

Add to `resources/views/items/index.blade.php`:

```blade
<form action="{{ route('items.print_bulk_qr') }}" method="POST" id="bulk-qr-form">
    @csrf
    
    <button type="submit" class="btn btn-primary">
        📄 Cetak Label QR Terpilih
    </button>
    
    <table>
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="select-all">
                </th>
                <th>Nama</th>
                <th>SN</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <input type="checkbox" 
                               name="selected_ids[]" 
                               value="{{ $item->id }}" 
                               class="item-checkbox">
                    </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>...</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>

<script>
    // Select All Functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
```

### Routes:

```php
// Single item QR (already exists)
GET /items/{item}/qr-pdf → Download single QR label

// Bulk QR (new)
POST /items/print-bulk-qr → Download multiple QR labels
```

---

## 🧪 Testing Checklist

### Maintenance Module:
- [ ] Create a maintenance schedule
- [ ] Click "Start" → Item status changes to `maintenance`
- [ ] Try to borrow the item → Should be blocked
- [ ] Click "Complete" → Item status returns to `available`
- [ ] Item can be borrowed again
- [ ] View maintenance history on item detail page

### Activity Logs:
- [ ] Install Spatie package: `composer require spatie/laravel-activitylog`
- [ ] Run migrations: `php artisan migrate`
- [ ] Create an item → Check `activity_log` table
- [ ] Update an item → Check `properties` JSON column
- [ ] Delete an item → Check log entry
- [ ] View logs via Tinker or admin UI

### QR Label Printing:
- [ ] Select 3 items from index page
- [ ] Click "Cetak Label QR" button
- [ ] PDF downloads successfully
- [ ] PDF contains 3 labels in grid layout
- [ ] QR codes are readable
- [ ] Text is clear and not cut off
- [ ] Print on A4 paper → Labels fit correctly

---

## 📦 Dependencies

```json
{
    "simplesoftwareio/simple-qrcode": "^4.2",      // ✅ Already installed
    "barryvdh/laravel-dompdf": "^3.1",             // ✅ Already installed
    "spatie/laravel-activitylog": "^4.0"           // ⚠️ NEED TO INSTALL
}
```

**Installation Command:**
```bash
composer require spatie/laravel-activitylog
```

---

## 🐛 Troubleshooting

### QR Code Not Showing in PDF

**Problem**: QR code appears as broken image in PDF

**Solution**:
```php
// In blade template, use base64 inline SVG
<img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(200)->generate($item->serial_number)) }}">
```

### Item Status Not Updating

**Problem**: Item status doesn't change when maintenance starts

**Solution**: Check database transaction in `MaintenanceController::start()`. Ensure both updates are wrapped in `DB::transaction()`.

### Maintenance History Not Showing

**Problem**: Maintenance records don't appear on item detail page

**Solution**: Add eager loading in ItemController:
```php
$item = Item::with('maintenances')->findOrFail($id);
```

### Activity Log Not Recording

**Problem**: Changes aren't logged to `activity_log` table

**Solution**:
1. Verify Spatie package is installed
2. Check `LogsActivity` trait is used in model
3. Ensure `getActivitylogOptions()` method exists
4. Clear cache: `php artisan config:clear`

---

## 🚀 Next Steps

### Recommended UI Enhancements:

1. **Item Detail Page** - Add "Maintenance History" card showing:
   - Past maintenances (completed)
   - Upcoming maintenances (pending)
   - Current status (in_progress)
   - Quick action buttons (Start/Complete)

2. **Maintenance Dashboard** - Create dedicated page showing:
   - Overdue maintenances (red alert)
   - This week's schedule (yellow warning)
   - Completed this month (green success)
   - Total cost per month (chart)

3. **Activity Log UI** - Create admin page showing:
   - Recent activities (last 50)
   - Filter by user, model, date range
   - Export to Excel
   - Search functionality

---

**Implementation Complete! 🎉**

All P2 features are ready for testing and integration.
