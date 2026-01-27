<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevController extends Controller
{
    /**
     * Display the Developer Dashboard.
     */
    /**
     * Display the Developer Dashboard.
     */
    public function index()
    {
        // Ambil semua user untuk impersonation list
        $users = \App\Models\User::all();
        // Opsional: Jika mau PeminjamUser juga
        // $peminjamUsers = \App\Models\PeminjamUser::all(); 

        return view('dev.index', compact('users'));
    }

    /**
     * User Impersonation Logic
     */
    public function impersonate($userId)
    {
        // Double check security
        if (auth()->user()->role !== 'dev' && !auth()->user()->isSuperAdmin() && !auth()->user()->is_dev_mode) {
            abort(403, 'Unauthorized action.');
        }

        $originalUserId = auth()->id();
        $user = \App\Models\User::findOrFail($userId);

        // Simpan ID asli ke session
        session()->put('impersonate_original_id', $originalUserId);

        // Login sebagai user target
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect('/dashboard')->with('success', "Mode Penyamaran Aktif! Anda sekarang login sebagai: {$user->name} ({$user->role})");
    }

    /**
     * Stop Impersonation
     */
    public function stopImpersonate()
    {
        if (session()->has('impersonate_original_id')) {
            $originalUserId = session()->pull('impersonate_original_id');
            $originalUser = \App\Models\User::find($originalUserId);

            if ($originalUser) {
                \Illuminate\Support\Facades\Auth::login($originalUser);
                return redirect()->route('dev.index')->with('success', 'Welcome back, Admin!');
            }
        }

        return redirect('/dashboard');
    }

    /**
     * Reset Database (Migrate Fresh + Seed).
     */
    public function resetDatabase(Request $request)
    {
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
