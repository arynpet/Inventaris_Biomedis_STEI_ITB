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

        // Time
        $seconds = $user->total_seconds_online ?? 0;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        // All Badge Definitions
        $allBadges = [
            ['name' => 'Newbie', 'icon' => 'fa-user', 'color' => 'gray', 'desc' => 'Joined the system', 'unlocked' => true],
            ['name' => 'Rookie', 'icon' => 'fa-seedling', 'color' => 'green', 'desc' => 'Reach Level 5', 'unlocked' => $level >= 5],
            ['name' => 'Veteran', 'icon' => 'fa-shield-halved', 'color' => 'blue', 'desc' => 'Reach Level 10', 'unlocked' => $level >= 10],
            ['name' => 'Elite', 'icon' => 'fa-gem', 'color' => 'indigo', 'desc' => 'Reach Level 20', 'unlocked' => $level >= 20],
            ['name' => 'Master', 'icon' => 'fa-crown', 'color' => 'yellow', 'desc' => 'Reach Level 30', 'unlocked' => $level >= 30],
            ['name' => 'Legend', 'icon' => 'fa-dragon', 'color' => 'red', 'desc' => 'Reach Level 50', 'unlocked' => $level >= 50],

            ['name' => 'Builder', 'icon' => 'fa-hammer', 'color' => 'teal', 'desc' => 'Create 10 Items', 'unlocked' => $creates >= 10],
            ['name' => 'Architect', 'icon' => 'fa-city', 'color' => 'emerald', 'desc' => 'Create 100 Items', 'unlocked' => $creates >= 100],
            ['name' => 'Creator', 'icon' => 'fa-paintbrush', 'color' => 'pink', 'desc' => 'Create 500 Items', 'unlocked' => $creates >= 500],

            ['name' => 'Editor', 'icon' => 'fa-pen-nib', 'color' => 'orange', 'desc' => 'Update 50 Items', 'unlocked' => $updates >= 50],
            ['name' => 'Maintainer', 'icon' => 'fa-screwdriver-wrench', 'color' => 'amber', 'desc' => 'Update 200 Items', 'unlocked' => $updates >= 200],

            ['name' => 'Cleaner', 'icon' => 'fa-broom', 'color' => 'slate', 'desc' => 'Delete 10 Items', 'unlocked' => $deletes >= 10],
            ['name' => 'Destroyer', 'icon' => 'fa-bomb', 'color' => 'red', 'desc' => 'Delete 50 Items', 'unlocked' => $deletes >= 50],

            ['name' => 'Time Traveler', 'icon' => 'fa-hourglass-start', 'color' => 'cyan', 'desc' => 'Online 1+ Hour', 'unlocked' => $hours >= 1],
            ['name' => 'Time Lord', 'icon' => 'fa-clock', 'color' => 'violet', 'desc' => 'Online 10+ Hours', 'unlocked' => $hours >= 10],
            ['name' => 'Chronos', 'icon' => 'fa-infinity', 'color' => 'fuchsia', 'desc' => 'Online 100+ Hours', 'unlocked' => $hours >= 100],
        ];

        // Extract simplified list for validation and existing views
        $unlockedBadges = array_column(array_filter($allBadges, fn($b) => $b['unlocked']), 'name');

        // Level Progress
        $currentLevelBaseXp = 100 * pow($level, 2);
        $nextLevelXpThreshold = 100 * pow($level + 1, 2);
        $xpNeeded = max(0, $nextLevelXpThreshold - $totalXp);
        $xpForNext = $nextLevelXpThreshold - $currentLevelBaseXp;
        $currentProgress = $totalXp - $currentLevelBaseXp;
        // Avoid division by zero
        $progressPercent = $xpForNext > 0 ? ($currentProgress / $xpForNext) * 100 : 100;
        $progressPercent = max(0, min(100, $progressPercent));

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
            'all_badges' => $allBadges, // <--- New Full List
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'bio' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'equipped_badges' => 'nullable|array|max:3',
            'equipped_badges.*' => 'string'
        ]);

        // Default to current badges if not present in request (though checkboxes usually send empty)
        // Actually, for checkboxes, if none checked, it sends nothing? Or user clears them.
        // We assume input is provided if it's a form submission.

        $badgeInput = $request->input('equipped_badges', []);

        $data = [
            'bio' => $request->bio,
        ];

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Store on 'public' disk. Path returned is 'avatars/filename.jpg'
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path;
        }

        // Verify Badge Ownership
        $stats = $this->calculateStats($user);
        $validBadges = [];

        foreach ($badgeInput as $badge) {
            if (in_array($badge, $stats->unlocked_badges)) {
                $validBadges[] = $badge;
            }
        }

        // Enforce Limit (Max 3)
        $data['equipped_badges'] = array_slice($validBadges, 0, 3);

        // Use Eloquent update to trigger Casts (array -> json)
        $user->update($data);

        return redirect()->route('profile.show', $user->id)->with('success', 'Profile updated successfully!');
    }
}
