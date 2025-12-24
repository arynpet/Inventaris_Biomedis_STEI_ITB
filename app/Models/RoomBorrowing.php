<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class RoomBorrowing extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'room_id',
        'user_id',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
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
