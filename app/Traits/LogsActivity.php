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

            // Abaikan timestamps & credentials
            unset($changes['updated_at']);
            unset($changes['password']);
            unset($changes['remember_token']);
            unset($changes['two_factor_secret']);
            unset($changes['two_factor_recovery_codes']);

            $details = [];
            foreach ($changes as $key => $value) {
                $oldValue = $original[$key] ?? '-';

                // --- FIX ARRAY TO STRING ---
                if (is_array($value))
                    $value = json_encode($value);
                if (is_array($oldValue))
                    $oldValue = json_encode($oldValue);
                // ---------------------------

                // Format label: Ubah room_id jadi Ruangan
                $label = str_replace('_', ' ', $key);
                $label = ucfirst($label);

                $details[] = "<span class='font-bold text-gray-700'>{$label}</span>: dari <span class='text-red-500'>'{$oldValue}'</span> menjadi <span class='text-emerald-500'>'{$value}'</span>";
            }

            if (!empty($details)) {
                $desc = "Mengupdate data " . self::getDisplayName($model) . ":<br>" . implode('<br>', $details);
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
        $userId = null;
        $userType = null; // Opsional: jika nanti tabel activity_logs mendukung polimorfik

        if (Auth::guard('web')->check()) {
            $userId = Auth::guard('web')->id();
        } elseif (Auth::guard('student')->check()) {
            $userId = Auth::guard('student')->id();
        }

        if ($userId) {
            ActivityLog::create([
                'user_id' => $userId,
                'action' => $action,
                'model' => class_basename($model),
                'model_id' => $model->id,
                'description' => $description,
                'ip_address' => request()->ip(),
            ]);
        }
    }

    // Helper untuk mengambil nama/judul data (agar log lebih terbaca)
    protected static function getDisplayName($model)
    {
        $baseName = class_basename($model);

        if ($baseName === 'Item') {
            return "Item '{$model->name}' (SN: " . ($model->serial_number ?? '-') . ")";
        }

        if ($baseName === 'Room') {
            return "Ruangan '{$model->name}'";
        }

        if ($model->name)
            return "'{$model->name}'";
        if ($model->project_name)
            return "'{$model->project_name}'";
        if ($model->code)
            return "'{$model->code}'";
        return "#" . $model->id;
    }
}