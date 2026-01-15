<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini
use App\Traits\LogsActivity; // <--- Import Trait

class Item extends Model
{
    use LogsActivity; // <--- Pasang CCTV disini
    use HasFactory;
    use SoftDeletes; // Gunakan ini

    protected $fillable = [
        'asset_number',
        'serial_number',
        'qr_code',
        'name',
        'brand',
        'type',
        'room_id',
        'quantity',
        'source',
        'acquisition_year',
        'placed_in_service_at',
        'fiscal_group',
        'status',
        'condition',
        'image_path', // Updated
    ];

    // --- ACCESSOR ---
    public function getOptimizedImageAttribute()
    {
        $value = $this->image_path;

        // 1. Jika kosong -> Placeholder
        if (empty($value)) {
            return 'https://placehold.co/400x300?text=No+Image';
        }

        // 2. Jika External URL (dimulai http/https) 
        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            // Gunakan Proxy wsrv.nl untuk mengatasi masalah Hotlink Protection / CORS
            $encodedUrl = urlencode($value);
            return "https://wsrv.nl/?url={$encodedUrl}&w=500&h=500&fit=cover&a=center";
        }

        // 3. Jika Local File -> Asset URL
        // Asumsi: file disimpan di storage/app/public/items, symlink ke public/storage/items
        return asset('storage/' . $value);
    }

    // Konversi otomatis tipe data
    protected $casts = [
        'placed_in_service_at' => 'date',
        'acquisition_year' => 'integer',
        'quantity' => 'integer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function latestLog()
    {
        return $this->hasOne(ItemOutLog::class)->latestOfMany();
    }
}