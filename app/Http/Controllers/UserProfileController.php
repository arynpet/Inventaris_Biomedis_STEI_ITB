<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    // Re-usable function to calculate Full Stats (Same as Gamification)
    private function calculateStats($user)
    {
        $tableName = 'activity_log';
        $userColumn = 'causer_id';
        $eventColumn = 'event';

        $rawStats = DB::table($tableName)
            ->where($userColumn, $user->id)
            ->select($eventColumn, DB::raw('count(*) as total'))
            ->groupBy($eventColumn)
            ->pluck('total', $eventColumn);

        $creates = $rawStats['created'] ?? 0;
        $updates = $rawStats['updated'] ?? 0;
        $deletes = $rawStats['deleted'] ?? 0;

        $baseXp = ($creates * 100) + ($updates * 20) + ($deletes * 10);
        $totalXp = $baseXp + ($user->bonus_xp ?? 0);

        // Level Calc
        $level = floor(sqrt($totalXp / 100));
        $level = max(1, $level);

        // Rank Name
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
            45 => 'Mythic',
            50 => 'Divine',
            60 => 'Immortal',
            70 => 'Ascended',
            80 => 'Godlike',
            90 => 'The System',
            100 => 'Administrator of Universe'
        ];
        $rankName = 'Novice';
        foreach ($ranks as $lvlReq => $name) {
            if ($level >= $lvlReq)
                $rankName = $name;
        }

        // Available Badges (Unlocked)
        $unlockedBadges = ['Newbie']; // Default
        if ($level >= 5)
            $unlockedBadges[] = 'Rookie';
        if ($level >= 10)
            $unlockedBadges[] = 'Veteran';
        if ($level >= 20)
            $unlockedBadges[] = 'Elite';
        if ($level >= 30)
            $unlockedBadges[] = 'Master';
        if ($level >= 50)
            $unlockedBadges[] = 'Legend';
        if ($creates >= 100)
            $unlockedBadges[] = 'Architect';
        if ($updates >= 200)
            $unlockedBadges[] = 'Maintainer';

        // Level Progress
        $currentLevelBaseXp = 100 * pow($level, 2);
        $nextLevelXpThreshold = 100 * pow($level + 1, 2);
        $xpNeeded = max(0, $nextLevelXpThreshold - $totalXp);
        $xpForNext = $nextLevelXpThreshold - $currentLevelBaseXp;
        $currentProgress = $totalXp - $currentLevelBaseXp;
        // Avoid division by zero
        $progressPercent = $xpForNext > 0 ? ($currentProgress / $xpForNext) * 100 : 100;
        $progressPercent = max(0, min(100, $progressPercent));

        // Time
        $seconds = $user->total_seconds_online ?? 0;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        // Advanced Stats
        $lastLog = DB::table($tableName)->where($userColumn, $user->id)->latest()->first();
        $lastAction = $lastLog ? ucfirst($lastLog->event) . " item" : 'No activity';
        $lastSeen = $lastLog ? Carbon::parse($lastLog->created_at)->diffForHumans() : 'Never';

        // Favorite Time of Day (Fun Stat)
        // Group activity by HOUR? Maybe too heavy. Let's do nothing for now.

        return (object) [
            'xp' => $totalXp,
            'level' => (int) $level,
            'rank_name' => $rankName,
            'creates' => $creates,
            'updates' => $updates,
            'deletes' => $deletes,
            'time_online' => "{$hours}h {$minutes}m",
            'unlocked_badges' => $unlockedBadges,
            // New Fields
            'next_level_xp' => $nextLevelXpThreshold,
            'xp_needed' => $xpNeeded,
            'progress_percent' => $progressPercent,
            'last_action' => $lastAction,
            'last_seen' => $lastSeen,
            'completion_rate' => $creates > 0 ? round(($updates / $creates) * 100) . '%' : 'N/A' // Just a random ratio
        ];
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $stats = $this->calculateStats($user);

        return view('profile.show_public', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();
        $stats = $this->calculateStats($user);

        return view('profile.setup', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'bio' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'equipped_badge' => 'nullable|string'
        ]);

        $data = [
            'bio' => $request->bio,
            'equipped_badge' => $request->equipped_badge ?? $user->equipped_badge
        ];

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists (optional logic)
            // if ($user->avatar_path) Storage::delete($user->avatar_path);

            // Store on 'public' disk. Path returned is 'avatars/filename.jpg'
            $path = $request->file('avatar')->store('avatars', 'public');

            $data['avatar_path'] = $path;
        }

        // Verify Badge Ownership (Simple Check)
        $stats = $this->calculateStats($user);
        if (!in_array($data['equipped_badge'], $stats->unlocked_badges)) {
            // If they try to equip a locked badge, revert to current or default
            $data['equipped_badge'] = $user->equipped_badge;
        }

        User::where('id', $user->id)->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
