<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HeartbeatController extends Controller
{
    public function beat(Request $request)
    {
        // Simple, lightweight, direct database update.
        // We use check() because user might not be logged in (e.g. login page).
        if (Auth::check()) {
            $id = Auth::id();

            try {
                // FORCE UPDATE using strict SQL
                $affected = DB::update("UPDATE users SET total_seconds_online = total_seconds_online + 10 WHERE id = ?", [$id]);

                // If 0 affected, maybe the row defaults are null? Try initializing
                if ($affected === 0) {
                    DB::update("UPDATE users SET total_seconds_online = 10 WHERE id = ?", [$id]);
                }

                // Return current value for UI
                $current = DB::table('users')->where('id', $id)->value('total_seconds_online');

                // TWEAK: Passive XP Farming
                // Every 10 minutes (600 seconds), award 5 Bonus XP
                $xpAwarded = false;
                if ($current > 0 && $current % 600 === 0) {
                    DB::update("UPDATE users SET bonus_xp = bonus_xp + 5 WHERE id = ?", [$id]);
                    $xpAwarded = true;
                }

                return response()->json([
                    'status' => 'pumped',
                    'val' => $current,
                    'xp_gain' => $xpAwarded
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error'], 500);
            }
        }

        return response()->json(['status' => 'guest']);
    }
}
