<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens; // <--- Import Sanctum
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity; // <--- Import Trait

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use LogsActivity; // <--- Pasang CCTV disini
    use HasApiTokens, HasFactory, Notifiable; // <--- Pasang HasApiTokens

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <--- Tambahkan ini
        'is_dev_mode', // <--- Developer Mode Toggle
    ];

    // Helper untuk cek apakah dia superadmin (Opsional, biar kodingan rapi)
    public function isSuperAdmin()
    {
        return in_array($this->role, ['superadmin', 'dev']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
