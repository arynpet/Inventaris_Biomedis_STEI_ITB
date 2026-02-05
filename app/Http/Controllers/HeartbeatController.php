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

                return response()->json([
                    'status' => 'pumped',
                    'val' => $current
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error'], 500);
            }
        }

        return response()->json(['status' => 'guest']);
    }
}
