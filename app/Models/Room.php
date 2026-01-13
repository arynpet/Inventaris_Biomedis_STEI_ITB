<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity; // <--- Import Trait

class Room extends Model
{
    use LogsActivity; // <--- Pasang CCTV disini
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
    ];

    // 1 Room -> banyak Items
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // 1 Room -> banyak Peminjaman Ruangan
    public function borrowings()
    {
        return $this->hasMany(RoomBorrowing::class);
    }
}
