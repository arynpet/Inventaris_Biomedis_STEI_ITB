<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevController extends Controller
{
    /**
     * Display the Developer Dashboard.
     */
    public function index()
    {
        // Security Check
        if (!auth()->user()->is_dev_mode) {
            abort(403, 'Developer Mode is not active.');
        }

        return view('dev.index');
    }

    /**
     * Reset Database (Migrate Fresh + Seed).
     */
    public function resetDatabase(Request $request)
    {
        // Security Check
        if (!auth()->user()->is_dev_mode) {
            abort(403, 'Developer Mode is not active.');
        }

        // Increase memory limit and execution time for heavy tasks
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        try {
            // Jalankan command
            \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');

            return back()->with('success', "Database has been nuked and re-seeded! 🤯 (Output: " . \Illuminate\Support\Facades\Artisan::output() . ")");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset database: ' . $e->getMessage());
        }
    }
}
