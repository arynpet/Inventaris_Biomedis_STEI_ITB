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

            // Get Base Stats
            $stats = $userStats[$userId] ?? ['xp' => 0, 'creates' => 0, 'updates' => 0, 'deletes' => 0];

            $baseXp = $stats['xp'];
            $bonusXp = (int) ($user->bonus_xp ?? 0);
            $totalXp = $baseXp + $bonusXp;

            // --- LEVELING ---
            $level = floor(sqrt($totalXp / 100));
            $level = max(1, $level);

            // Progress Config
            $currentLevelBaseXp = 100 * pow($level, 2);
            $nextLevelXpThreshold = 100 * pow($level + 1, 2);
            $xpNeeded = $nextLevelXpThreshold - $currentLevelBaseXp;
            $xpProgress = $totalXp - $currentLevelBaseXp;
            $progressPercent = $xpNeeded > 0 ? ($xpProgress / $xpNeeded) * 100 : 0;
            $progressPercent = min(100, max(0, $progressPercent));

            // --- RANKS (SYNCED) ---
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
            $rankIcon = 'fa-user'; // Default
            $rankColor = 'text-gray-500'; // Default

            // Map Ranks to Icons & Colors
            $rankMeta = [
                'Novice' => ['icon' => 'fa-user', 'color' => 'text-gray-500'],
                'Apprentice' => ['icon' => 'fa-scroll', 'color' => 'text-blue-600'],
                'Adept' => ['icon' => 'fa-book-open', 'color' => 'text-green-600'],
                'Specialist' => ['icon' => 'fa-flask', 'color' => 'text-teal-600'],
                'Expert' => ['icon' => 'fa-star', 'color' => 'text-indigo-600'],
                'Elite' => ['icon' => 'fa-medal', 'color' => 'text-indigo-800'],
                'Master' => ['icon' => 'fa-crown', 'color' => 'text-purple-600'],
                'Grandmaster' => ['icon' => 'fa-chess-king', 'color' => 'text-purple-800'],
                'Legend' => ['icon' => 'fa-dragon', 'color' => 'text-red-500'],
                'Mythic' => ['icon' => 'fa-dungeon', 'color' => 'text-red-700'],
                'Divine' => ['icon' => 'fa-sun', 'color' => 'text-yellow-600'],
                'Immortal' => ['icon' => 'fa-infinity', 'color' => 'text-yellow-800'],
                'Ascended' => ['icon' => 'fa-cloud-bolt', 'color' => 'text-cyan-600'],
                'Godlike' => ['icon' => 'fa-bolt', 'color' => 'text-cyan-800'],
                'The System' => ['icon' => 'fa-microchip', 'color' => 'text-gray-900'],
                'Administrator of Universe' => ['icon' => 'fa-globe', 'color' => 'text-red-600']
            ];

            foreach ($ranks as $lvlReq => $name) {
                if ($level >= $lvlReq) {
                    $rankName = $name;
                    $rankIcon = $rankMeta[$name]['icon'] ?? 'fa-user';
                    $rankColor = $rankMeta[$name]['color'] ?? 'text-gray-500';
                }
            }

            // Time Online
            $seconds = $user->total_seconds_online ?? 0;
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $timeString = ($hours > 0 ? "{$hours}h " : "") . "{$minutes}m";

            // --- BADGES (SYNCED) ---
            $unlocked = ['Newbie'];
            if ($level >= 5)
                $unlocked[] = 'Rookie';
            if ($level >= 10)
                $unlocked[] = 'Veteran';
            if ($level >= 20)
                $unlocked[] = 'Elite';
            if ($level >= 30)
                $unlocked[] = 'Master';
            if ($level >= 50)
                $unlocked[] = 'Legend';

            if ($stats['creates'] >= 10)
                $unlocked[] = 'Builder';
            if ($stats['creates'] >= 100)
                $unlocked[] = 'Architect';
            if ($stats['creates'] >= 500)
                $unlocked[] = 'Creator';

            if ($stats['updates'] >= 50)
                $unlocked[] = 'Editor';
            if ($stats['updates'] >= 200)
                $unlocked[] = 'Maintainer';

            if ($stats['deletes'] >= 10)
                $unlocked[] = 'Cleaner';
            if ($stats['deletes'] >= 50)
                $unlocked[] = 'Destroyer';

            if ($hours >= 1)
                $unlocked[] = 'Time Traveler';
            if ($hours >= 10)
                $unlocked[] = 'Time Lord';
            if ($hours >= 100)
                $unlocked[] = 'Chronos';

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_path' => $user->avatar_path,
                'avatar_initial' => substr($user->name, 0, 1),
                'equipped_badges' => $user->equipped_badges ?? [], // Pass equipped list
                'xp' => $totalXp,
                'level' => (int) $level,
                'rank_name' => $rankName,
                'rank_icon' => $rankIcon,
                'rank_color' => $rankColor, // <--- Added
                'next_level_xp' => $nextLevelXpThreshold,
                'progress_percent' => $progressPercent,
                'total_creates' => $stats['creates'],
                'total_updates' => $stats['updates'],
                'total_deletes' => $stats['deletes'],
                'all_badges' => $unlocked, // List of names
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
