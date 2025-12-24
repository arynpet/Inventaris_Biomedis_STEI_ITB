<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamUser extends Model
{
    protected $table = 'peminjam_users';

    use HasFactory;
    
    protected $fillable = [
        'name',
        'nim',
        'email',
        'phone',
        'role',
        'is_trained',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'user_id');
    }
}
