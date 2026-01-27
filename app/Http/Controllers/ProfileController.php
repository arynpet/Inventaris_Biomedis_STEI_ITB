<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    /**
     * Toggle Developer Mode.
     */
    /**
     * Toggle Developer Mode.
     */
    public function updateDevMode(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Simple Toggle
        $user->update([
            'is_dev_mode' => !$user->is_dev_mode
        ]);

        $message = $user->is_dev_mode ? 'Mode Pengembang AKTIF 🚀' : 'Mode Pengembang NONAKTIF.';

        return back()->with('status', $message);
    }

    /**
     * Unlock Developer Role (Easter Egg)
     */
    public function upgradeDev(Request $request)
    {
        // 1. Jika Superadmin, langsung lolos
        if ($request->user()->isSuperAdmin()) {
            $request->user()->update([
                'role' => 'dev', // Opsional, superadmin sudah include dev privileges
                'is_dev_mode' => true
            ]);
            return response()->json(['success' => true, 'message' => 'Dev Mode Activated (Superadmin)!']);
        }

        // 2. Jika user biasa, butuh password
        $request->validate([
            'password' => 'required|string',
        ]);

        if ($request->password === 'akucintakamu0212') {
            $request->user()->update([
                'role' => 'dev',
                'is_dev_mode' => true
            ]);
            return response()->json(['success' => true, 'message' => 'Developer Role Unlocked!']);
        }

        return response()->json(['success' => false, 'message' => 'Wrong Password!'], 403);
    }
}
