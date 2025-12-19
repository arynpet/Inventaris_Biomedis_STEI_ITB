<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'borrow_date',
        'return_date',
        'return_condition', // <--- Kolom Baru
        'status',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke Peminjam (peminjam_users)
    public function borrower()
    {
        return $this->belongsTo(PeminjamUser::class, 'user_id');
    }
}