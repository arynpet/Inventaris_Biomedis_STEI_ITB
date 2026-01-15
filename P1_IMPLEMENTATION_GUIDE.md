# P1 Feature Implementation Guide
## Notification & Reporting System

**Date**: 2026-01-15  
**Status**: ✅ Ready for Testing  
**Priority**: P1 (Essential Features)

---

## 📧 Feature 1: Overdue Item Notification System

### What Was Implemented:

1. **Notification Class** (`app/Notifications/OverdueItemNotification.php`)
   - Sends email to students with overdue items
   - Also stores notifications in database for in-app display
   - Uses queue (ShouldQueue) for better performance
   - Email includes:
     - Student name
     - Item name
     - Original return date
     - Days overdue
     - Action button to view loan details

2. **Automated Scheduler** (`routes/console.php`)
   - Runs **daily at 08:00 AM** (server time)
   - Queries loans with status `'active'` or `'approved'`
   - Filters loans where `return_date` < today
   - Sends email notification to each student
   - Logs the number of emails sent

3. **Manual Test Command**
   ```bash
   php artisan notify:overdue
   ```
   Use this to test the notification system without waiting for 08:00 AM.

### How to Configure:

#### Step 1: Setup Mail Configuration

Edit `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Lab Biomedis STEI ITB"
```

**For Gmail**: Use "App Password" instead of regular password.  
[Generate App Password](https://support.google.com/accounts/answer/185833)

#### Step 2: Create Notifications Table (if not exists)

```bash
php artisan notifications:table
php artisan migrate
```

#### Step 3: Start Laravel Scheduler

**On Windows (Development)**:
```bash
# Run this in a separate terminal (keep it running)
php artisan schedule:work
```

**On Production (Linux)**:
Add to crontab:
```bash
crontab -e
```
Add this line:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Testing:

1. **Create test overdue loan**:
   ```sql
   UPDATE loans 
   SET return_date = DATE_SUB(NOW(), INTERVAL 2 DAY), 
       status = 'approved'
   WHERE id = 1;
   ```

2. **Run manual command**:
   ```bash
   php artisan notify:overdue
   ```

3. **Check email** in student's inbox

4. **Check database notifications**:
   ```sql
   SELECT * FROM notifications WHERE type = 'App\\Notifications\\OverdueItemNotification';
   ```

---

## 📊 Feature 2: Reporting & Export System

### What Was Implemented:

#### A. Excel Export (Inventory Data)

**File**: `app/Exports/ItemsExport.php`

**Features**:
- Export ALL inventory items
- Columns: ID, Name, Serial, Asset, Condition, Status, Location, Categories, Year, Source, Created Date
- Professional styling (blue header, alternating rows)
- Auto-sized columns

**Route**: `GET /reports/items/excel`

#### B. PDF Monthly Loan Report

**File**: `resources/views/reports/monthly_pdf.blade.php`

**Features**:
- Professional letterhead (Lab Biomedis STEI ITB)
- Filter by Month & Year
- Table of all loans in that period
- Status badges (color-coded)
- Summary statistics

**Route**: `POST /reports/monthly/pdf`

#### C. Admin UI

**File**: `resources/views/reports/index.blade.php`

**Features**:
- Beautiful card-based layout
- Excel export button (green)
- PDF export with month/year filter (red)
- Responsive design

**Route**: `GET /reports`

### How to Access:

1. **Go to Reports Page**:
   ```
   http://your-domain/reports
   ```

2. **Export Excel**:
   - Click "Download Excel" button
   - File downloads immediately
   - Filename: `Inventaris_Lab_Biomedis_2026-01-15_143022.xlsx`

3. **Generate Monthly PDF**:
   - Select month (dropdown)
   - Select year (dropdown)
   - Click "Generate PDF"
   - File downloads: `Laporan_Peminjaman_Januari_2026.pdf`

### Adding to Sidebar:

Edit `resources/views/layouts/sidebar.blade.php`, add this menu item:

```blade
{{-- Reports Section --}}
<div class="py-2">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4 mb-2">
        📊 Laporan
    </p>
    <a href="{{ route('reports.index') }}" 
       class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span class="font-medium">Export & Laporan</span>
    </a>
</div>
```

---

## 🔒 Security Notes

1. **Authorization**: All report routes are protected by `['auth', 'verified']` middleware.
2. **Rate Limiting**: Consider adding throttle if reports are heavy:
   ```php
   Route::get('/items/excel', ...)->middleware('throttle:10,1');
   ```

3. **File Access**: Downloaded files don't expose sensitive paths (generated on-the-fly).

---

## 📦 Dependencies (Already Installed)

```json
{
    "maatwebsite/excel": "^3.1",      // ✅ Installed
    "barryvdh/laravel-dompdf": "^3.1" // ✅ Installed
}
```

No additional installation required!

---

## 🧪 Testing Checklist

### Notification System:
- [ ] Mail configuration in `.env` is correct
- [ ] `notifications` table exists
- [ ] `php artisan notify:overdue` sends test email
- [ ] Scheduler is running (`php artisan schedule:work`)
- [ ] Email arrives in student inbox
- [ ] Database notification is created

### Excel Export:
- [ ] Excel file downloads successfully
- [ ] All columns are populated
- [ ] Formatting looks professional
- [ ] File opens in Microsoft Excel/Google Sheets

### PDF Report:
- [ ] PDF downloads successfully
- [ ] Letterhead displays correctly
- [ ] Data filters by selected month/year
- [ ] Empty state message shows when no data
- [ ] PDF is readable (no encoding issues)

---

## 📈 Performance Notes

- **Excel Export**: Can handle 10,000+ records. For larger datasets, consider chunking.
- **PDF Export**: Paginated for 100+ records. Consider adding summary-only option for very large datasets.
- **Email Notifications**: Queued by default. Ensure queue worker is running:
  ```bash
  php artisan queue:work
  ```

---

## 🐛 Troubleshooting

### "Class 'Excel' not found"

```bash
composer dump-autoload
php artisan optimize:clear
```

### Emails not sending

1. Check `.env` mail configuration
2. Test with `php artisan tinker`:
   ```php
   Mail::raw('Test email', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

3. Check Laravel logs: `storage/logs/laravel.log`

### PDF blank or broken

1. Clear cache:
   ```bash
   php artisan view:clear
   php artisan config:clear
   ```

2. Check DomPDF version compatibility (should be 3.1+)

---

## 🚀 Next Steps (Optional Enhancements)

### P2 Features:
1. **WhatsApp Notifications** (via Twilio)
2. **Customizable Email Templates** (admin panel)
3. **Scheduled Reports** (auto-send monthly reports to admin email)
4. **Export to CSV** (lightweight alternative to Excel)
5. **QR Code Batch Print** (PDF with multiple QR codes per page)

---

**Implementation Complete! 🎉**  
All files have been created and are ready for testing.
