<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBorrowing extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'notes',
    ];

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi ke user peminjam
    public function user()
    {
        return $this->belongsTo(PeminjamUser::class, 'user_id');
    }
}
