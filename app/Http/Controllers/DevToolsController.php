<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog; // Though we don't need activity log for XP calc here as it is computed on fly in GamificationController, we need to show raw XP maybe? 
// Wait, GamificationController calculates XP on the fly. 
// For this view, we want to edit User model's bonus_xp and total_seconds_online.

class DevToolsController extends Controller
{
    public function login()
    {
        return view('devtools.login');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        if ($request->password === 'ikanbasahdigorengkering') {
            session(['god_mode_unlocked' => true]);
            return redirect()->route('dev.tools.index');
        }

        return back()->with('error', 'Access Denied.');
    }

    public function index()
    {
        // We need to show "Calculated Real XP" to help the admin know what base they are editing on top of.
        // We can reuse logic from GamificationController or just do a quick calc here.
        // For efficiency, let's just grab users and show their current stored bonus.
        // Calculating total XP on the fly for everyone might be heavy but for a dev tool it is fine.

        $users = User::all();

        // Calculate Base XP for display 
        // Logic copied from GamificationController roughly
        $baseXpMap = [];
        try {
            $rawStats = DB::table('activity_log')
                ->select('causer_id', 'event', DB::raw('count(*) as total'))
                ->whereNotNull('causer_id')
                ->groupBy('causer_id', 'event')
                ->get();

            foreach ($rawStats as $row) {
                if (!isset($baseXpMap[$row->causer_id])) {
                    $baseXpMap[$row->causer_id] = 0;
                }
                if ($row->event == 'created')
                    $baseXpMap[$row->causer_id] += ($row->total * 100);
                if ($row->event == 'updated')
                    $baseXpMap[$row->causer_id] += ($row->total * 20);
                if ($row->event == 'deleted')
                    $baseXpMap[$row->causer_id] += ($row->total * 10);
            }
        } catch (\Exception $e) {
            // Ignore if table missing
        }

        return view('devtools.god_mode', compact('users', 'baseXpMap'));
    }

    public function update(Request $request, $id)
    {
        // FORCE UPDATE bypasses Model events/timestamps if needed, perfectly strictly.
        $updated = User::where('id', $id)->update([
            'bonus_xp' => (int) $request->input('bonus_xp'),
            'total_seconds_online' => (int) $request->input('total_seconds_online')
        ]);

        if ($updated) {
            return back()->with('success', "FORCED UPDATED [ID:$id]. XP: {$request->bonus_xp}, Secs: {$request->total_seconds_online}");
        }

        return back()->with('error', 'Update Failed. User may not exist.');
    }
}
