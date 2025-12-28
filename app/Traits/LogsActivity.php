<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    // Method 'boot' pada Trait akan otomatis dijalankan oleh Model
    protected static function bootLogsActivity()
    {
        // 1. Saat Data Dibuat (Created)
        static::created(function ($model) {
            self::saveLog($model, 'create', "Menambahkan data baru: " . self::getDisplayName($model));
        });

        // 2. Saat Data Diupdate (Updated)
        static::updated(function ($model) {
            $changes = $model->getChanges(); // Ambil kolom apa saja yang berubah
            $original = $model->getOriginal();
            
            // Abaikan timestamps update
            unset($changes['updated_at']);

            $details = [];
            foreach ($changes as $key => $value) {
                $oldValue = $original[$key] ?? '-';
                $details[] = ucfirst($key) . " dari '{$oldValue}' menjadi '{$value}'";
            }

            if (!empty($details)) {
                $desc = "Mengupdate data " . self::getDisplayName($model) . ": " . implode(', ', $details);
                self::saveLog($model, 'update', $desc);
            }
        });

        // 3. Saat Data Dihapus (Deleted)
        static::deleted(function ($model) {
            self::saveLog($model, 'delete', "Menghapus data: " . self::getDisplayName($model));
        });
    }

    // Fungsi Simpan ke Database
    protected static function saveLog($model, $action, $description)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'model'       => class_basename($model), // Ambil nama class saja (contoh: Print3D)
                'model_id'    => $model->id,
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        }
    }

    // Helper untuk mengambil nama/judul data (agar log lebih terbaca)
    protected static function getDisplayName($model)
    {
        // Coba cari kolom 'name', 'project_name', atau 'title'
        if ($model->name) return $model->name;
        if ($model->project_name) return $model->project_name;
        if ($model->code) return $model->code;
        return '#' . $model->id;
    }
}