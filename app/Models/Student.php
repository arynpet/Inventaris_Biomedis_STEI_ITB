<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nim',
        'email',
        'class',
        'phone',
    ];

    // Karena kita pakai NIM sebagai password (tanpa hashing untuk simplifikasi sesuai request, 
    // meski best practicenya harus di-hash), kita perlu override auth password behavior 
    // atau kita hanya manual login tanpa Auth::attempt.
    // OPSI TERBAIK: Tetap gunakan Student extends Authenticatable agar bisa 'Auth::guard->login($user)'
}
