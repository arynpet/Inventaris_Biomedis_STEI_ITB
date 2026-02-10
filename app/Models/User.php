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
        'bonus_xp', // <--- God Mode
        'total_seconds_online', // <--- God Mode
        'avatar_path',
        'bio',
        'bio',
        'equipped_badges', // <--- Changed to plural
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
            'equipped_badges' => 'array', // <--- Auto JSON Cast
        ];
    }
    // --- GAMIFICATION ACCESSORS (Cached for Performance) ---

    /**
     * Get Total XP (Activity + Bonus) - Cached for 10 mins
     */
    public function getTotalXpAttribute()
    {
        return cache()->remember("user_xp_{$this->id}", 600, function () {
            // NOTE: Using 'activity_log' table name explicitly as in Controller
            $creates = \Illuminate\Support\Facades\DB::table('activity_log')
                ->where('causer_id', $this->id)->where('event', 'created')->count();

            $updates = \Illuminate\Support\Facades\DB::table('activity_log')
                ->where('causer_id', $this->id)->where('event', 'updated')->count();

            $deletes = \Illuminate\Support\Facades\DB::table('activity_log')
                ->where('causer_id', $this->id)->where('event', 'deleted')->count();

            return ($creates * 100) + ($updates * 20) + ($deletes * 10) + ($this->bonus_xp ?? 0);
        });
    }

    public function getLevelAttribute()
    {
        $xp = $this->total_xp;
        $level = floor(sqrt($xp / 100));
        return max(1, (int) $level);
    }

    public function getRankNameAttribute()
    {
        $level = $this->level;
        $ranks = [
            0 => 'Novice',
            5 => 'Apprentice',
            10 => 'Adept',
            15 => 'Specialist',
            20 => 'Expert',
            25 => 'Elite',
            30 => 'Master',
            35 => 'Grandmaster',
            40 => 'Legend',
            50 => 'Divine',
            60 => 'Immortal',
            100 => 'Godlike'
        ];
        $rankName = 'Novice';
        foreach ($ranks as $lvl => $name) {
            if ($level >= $lvl)
                $rankName = $name;
        }
        return $rankName;
    }
}
