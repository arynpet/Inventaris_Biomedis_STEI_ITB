<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // PENTING: Extend Authenticatable
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class PeminjamUser extends Authenticatable
{
    use LogsActivity;
    use HasFactory, Notifiable;

    protected $table = 'peminjam_users';

    protected $fillable = [
        'name',
        'nim',
        'email',
        'phone',
        'role',
        'is_trained',
        'password', // Add password to fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_trained' => 'boolean',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'user_id');
    }

    public function getIsDefaultPasswordAttribute()
    {
        // Jika password NULL, anggap default (login pakai NIM biasa)
        if (!$this->password)
            return true;
        // Cek apakah password cocok dengan Hash(NIM)
        return $this->nim && \Illuminate\Support\Facades\Hash::check($this->nim, $this->password);
    }
}
