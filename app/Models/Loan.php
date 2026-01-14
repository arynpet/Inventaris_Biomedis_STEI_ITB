<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'purpose',
        'borrow_date',
        'return_date',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'quantity' => 'integer',
    ];

    public function user()
    {
        // Explicitly link to PeminjamUser
        return $this->belongsTo(PeminjamUser::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
