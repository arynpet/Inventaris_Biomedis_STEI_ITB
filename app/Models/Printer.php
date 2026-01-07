<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use App\Traits\LogsActivity; // <--- Import Trait

class Printer extends Model
{
        use LogsActivity; // <--- Pasang CCTV disini
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',        // filament / resin
        'material_type_id',
        'status',
        'available_at',
    ];

    protected $casts = [
        'available_at' => 'datetime',
    ];

    // Relasi kategori printer → material_types berdasarkan kolom category
    public function materialTypes()
    {
        return $this->hasMany(MaterialType::class, 'category', 'category');
    }

    // Format available_at untuk index
    public function getAvailableAtFormattedAttribute()
    {
        return $this->available_at
            ? Carbon::parse($this->available_at)->format('d M Y H:i')
            : '-';
    }
}
