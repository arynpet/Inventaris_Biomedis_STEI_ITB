# P2 Feature Implementation - Part 2: Activity Logging

## Installing Spatie Activity Log

### Step 1: Install Package

```bash
composer require spatie/laravel-activitylog
```

### Step 2: Publish Migration & Config

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
```

### Step 3: Run Migration

```bash
php artisan migrate
```

This will create the `activity_log` table with columns:
- `id`
- `log_name`
- `description`
- `subject_type` (Model class)
- `subject_id`
- `causer_type` (User model)
- `causer_id`
- `properties` (JSON - stores changes)
- `created_at`

### Step 4: Configuration (Optional)

Edit `config/activitylog.php` if needed:

```php
return [
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),
    
    'delete_records_older_than_days' => 365, // Keep 1 year of logs

    'default_log_name' => 'default',

    'default_auth_driver' => null, // Uses default auth driver

    'subject_returns_soft_deleted_models' => false,

    'activity_model' => \Spatie\Activitylog\Models\Activity::class,
];
```

### Usage:

The package will automatically log:
- **created**: When a model is created
- **updated**: When a model is updated (with old/new values)  
- **deleted**: When a model is deleted

Access log data:
```php
// Get all activities
$activities = Activity::all();

// Get activities for specific model
$activities = Activity::forSubject($item)->get();

// Get activities by specific user
$activities = Activity::causedBy(auth()->user())->get();
```

## Manual Logging Examples

```php
// Log custom activity
activity()
   ->performedOn($item)
   ->causedBy(auth()->user())
   ->withProperties(['custom' => 'data'])
   ->log('Custom action performed');

// Log without model
activity()
   ->causedBy(auth()->user())
   ->log('User logged in');
```

## Testing:

1. Create an item → Check `activity_log` table
2. Update an item → Check `properties` column for changes
3. Delete an item → Check log entry

```sql
SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 10;
```
