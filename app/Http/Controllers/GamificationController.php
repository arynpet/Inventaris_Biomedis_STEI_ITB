<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    public function index()
    {
        // ----------------------------------------------------------------
        // CONFIGURATION: Adjust these to match your actual DB Table
        // ----------------------------------------------------------------
        // Note: Standard Spatie/Laravel-ActivityLog uses 'activity_log', 'causer_id', 'event'
        // Note: Your custom log might use 'activity_logs', 'user_id', 'action'

        $tableName = 'activity_log';   // Default to Spatie standard as requested for "historical data"
        $userColumn = 'causer_id';     // Default to Spatie standard
        $eventColumn = 'event';        // Default to Spatie standard

        // Event Values in Database
        $evtCreated = 'created';       // Standard Spatie
        $evtUpdated = 'updated';       // Standard Spatie
        $evtDeleted = 'deleted';       // Standard Spatie

        // UNCOMMENT THIS BLOCK IF YOU USE CUSTOM 'activity_logs' TABLE 
        /*
        $tableName = 'activity_logs';
        $userColumn = 'user_id';
        $eventColumn = 'action';
        $evtCreated = 'create';
        $evtUpdated = 'update';
        $evtDeleted = 'delete';
        */
        // ----------------------------------------------------------------

        // 1. Query All Historical Data
        // We group by User AND Event Type to get counts efficiently
        // Result Example: [{user_id: 1, event: 'created', total: 50}, {user_id: 1, event: 'updated', total: 20}]

        try {
            $rawStats = DB::table($tableName)
                ->select($userColumn, $eventColumn, DB::raw('count(*) as total'))
                ->whereNotNull($userColumn)
                ->groupBy($userColumn, $eventColumn)
                ->get();
        } catch (\Exception $e) {
            // Fallback if table doesn't exist to prevent crash
            return back()->with('error', "Gamification Error: Table '$tableName' not found. Please check Controller config.");
        }

        // 2. Process Data in PHP
        // Transform the raw rows into a User-keyed array
        $userStats = [];

        foreach ($rawStats as $row) {
            $uid = $row->$userColumn;
            $evt = $row->$eventColumn;
            $count = $row->total;

            if (!isset($userStats[$uid])) {
                $userStats[$uid] = [
                    'xp' => 0,
                    'creates' => 0,
                    'updates' => 0,
                    'deletes' => 0
                ];
            }

            // Calculate XP based on Event Type
            if ($evt == $evtCreated) {
                $userStats[$uid]['xp'] += ($count * 100);
                $userStats[$uid]['creates'] += $count;
            } elseif ($evt == $evtUpdated) {
                $userStats[$uid]['xp'] += ($count * 20);
                $userStats[$uid]['updates'] += $count;
            } elseif ($evt == $evtDeleted) {
                $userStats[$uid]['xp'] += ($count * 10);
                $userStats[$uid]['deletes'] += $count;
            }
        }

        // 3. Map to Users and Sort Leaderboard
        $allUsers = User::all();

        $leaderboard = $allUsers->map(function ($user) use ($userStats) {
            $userId = $user->id;

            // Get Base Stats from Activity Log or specific default
            $stats = $userStats[$userId] ?? [
                'xp' => 0,
                'creates' => 0,
                'updates' => 0,
                'deletes' => 0
            ];

            // Calculate Base XP + Bonus XP
            $baseXp = $stats['xp'];
            $bonusXp = (int) ($user->bonus_xp ?? 0);
            $totalXp = $baseXp + $bonusXp;

            // If total XP is 0 (and no online time), we might want to skip or rank lowest
            // For now, let's process everyone

            // --- LEVELING ALGORITHM ---
            $level = floor(sqrt($totalXp / 100));
            $level = max(1, $level);

            // Progress Config
            $currentLevelBaseXp = 100 * pow($level, 2);
            $nextLevelXpThreshold = 100 * pow($level + 1, 2);

            $xpNeeded = $nextLevelXpThreshold - $currentLevelBaseXp;
            $xpProgress = $totalXp - $currentLevelBaseXp;

            $progressPercent = $xpNeeded > 0 ? ($xpProgress / $xpNeeded) * 100 : 0;
            $progressPercent = min(100, max(0, $progressPercent));

            // --- RANK TITLES ---
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

            // --- BADGES ---
            $badges = [];
            if ($stats['creates'] >= 10)
                $badges[] = 'Builder 🥉';
            if ($stats['creates'] >= 100)
                $badges[] = 'Architect 🥈';
            if ($stats['creates'] >= 500)
                $badges[] = 'Creator 🥇';

            if ($stats['updates'] >= 50)
                $badges[] = 'Editor 🥉';
            if ($stats['updates'] >= 200)
                $badges[] = 'Maintainer 🥈';
            if ($stats['updates'] >= 1000)
                $badges[] = 'Polisher 🥇';

            if ($stats['deletes'] >= 10)
                $badges[] = 'Cleaner 🧹';
            if ($stats['deletes'] >= 50)
                $badges[] = 'Destroyer 💀';

            if ($totalXp >= 10000)
                $badges[] = 'High Roller 💎';
            if ($totalXp >= 50000)
                $badges[] = 'Elite Club 🎩';
            if ($totalXp >= 100000)
                $badges[] = 'Inventory God ⚡';

            // Top Badge
            $topBadge = !empty($badges) ? end($badges) : null;

            // Time Online
            $seconds = $user->total_seconds_online ?? 0;
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $timeString = ($hours > 0 ? "{$hours}h " : "") . "{$minutes}m";

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_path' => $user->avatar_path, // <--- Added this line
                'avatar_initial' => substr($user->name, 0, 1),
                'xp' => $totalXp,
                'level' => (int) $level,
                'rank_name' => $rankName,
                'next_level_xp' => $nextLevelXpThreshold,
                'progress_percent' => $progressPercent,
                'total_creates' => $stats['creates'],
                'total_updates' => $stats['updates'],
                'total_deletes' => $stats['deletes'],
                'badge' => $topBadge,
                'all_badges' => $badges,
                'time_online' => $timeString
            ];
        })->sortByDesc('xp')->values();

        // 4. Get Current User Stats
        $currentUser = $leaderboard->firstWhere('id', Auth::id());

        if (!$currentUser && Auth::check()) {
            $currentUser = (object) [
                'name' => Auth::user()->name,
                'level' => 1,
                'xp' => (int) (Auth::user()->bonus_xp ?? 0),
                'next_level_xp' => 100,
                'progress_percent' => 0,
                'rank_name' => 'Novice',
                'badge' => 'Newcomer',
                'all_badges' => [],
                'total_seconds_online' => (int) (Auth::user()->total_seconds_online ?? 0)
            ];
        }

        return view('gamification.index', compact('leaderboard', 'currentUser'));
    }

    public function heartbeat(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            try {
                // 1. UPDATE
                // Use simple SQL to increment
                $affected = DB::update("UPDATE users SET total_seconds_online = COALESCE(total_seconds_online, 0) + 10 WHERE id = ?", [$user->id]);

                // 2. FETCH LATEST VALUE
                $newVal = DB::table('users')->where('id', $user->id)->value('total_seconds_online');

                // 3. LOG (Optional, good for verification)
                // \Log::info("Heartbeat: User {$user->id} -> {$newVal}s (Affected: $affected)");

                return response()->json([
                    'status' => 'pumped',
                    'affected' => $affected,
                    'new_seconds' => $newVal
                ]);

            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'msg' => $e->getMessage()], 500);
            }
        }
        return response()->json(['status' => 'guest'], 401);
    }
}
