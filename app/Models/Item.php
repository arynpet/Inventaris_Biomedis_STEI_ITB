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
        'room_id',
        'quantity',
        'source',
        'acquisition_year',
        'placed_in_service_at',
        'fiscal_group',
        'status',      // Status ketersediaan (available, borrowed, maintenance)
        'condition',   // Status fisik barang (good, damaged, broken) -> BARU
    ];

    // Konversi otomatis tipe data
    protected $casts = [
        'placed_in_service_at' => 'date',
        'acquisition_year'     => 'integer',
        'quantity'             => 'integer',
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