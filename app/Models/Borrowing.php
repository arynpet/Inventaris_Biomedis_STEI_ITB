<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Borrowing extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'quantity', // ✅ Added quantity
        'borrow_date',
        'return_date',
        'return_condition',
        'status',
        'notes',
        'ruang_pakai',
        'penanggung_jawab',
        'follow_up',
        'evidence_photo',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'quantity' => 'integer',
    ];

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    // Relasi ke Peminjam (peminjam_users)
    public function borrower()
    {
        return $this->belongsTo(PeminjamUser::class, 'user_id');
    }
}