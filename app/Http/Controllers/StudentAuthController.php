<?php

namespace App\Http\Controllers;

use App\Models\PeminjamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
    /**
     * Handle Student Registration
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:peminjam_users,nim', // Table name updated
            'email' => 'required|email|unique:peminjam_users,email', // Table name updated
            'phone' => 'nullable|string|max:20',
        ]);

        // Simpan Data Peminjam
        // Password diisi NIM yang di-hash
        $student = PeminjamUser::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->nim), // Password = NIM
            'role' => 'mahasiswa', // Default Role
        ]);

        // Auto Login setelah registrasi
        Auth::guard('student')->login($student);

        return redirect()->route('public.catalog')->with('success', 'Registrasi berhasil! Selamat datang, ' . $student->name);
    }

    /**
     * Handle Student Login
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nim' => 'required|string', // NIM as Password input
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->nim, // Map 'nim' input to 'password' credential
        ];

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('public.catalog'))->with('success', 'Login berhasil!');
        }

        // Fallback untuk user lama yang passwordnya mungkin masih NULL
        $student = PeminjamUser::where('email', $request->email)->first();
        
        if ($student) {
            // Check 1: Password NULL (legacy user)
            if (!$student->password && $student->nim === $request->nim) {
                // Update password dengan hash
                $student->update(['password' => Hash::make($request->nim)]);
                
                Auth::guard('student')->login($student);
                return redirect()->intended(route('public.catalog'))
                    ->with('success', 'Login berhasil (Security Updated)!');
            }
            
            // Check 2: Password exists tapi Hash::check gagal di Auth::attempt
            // (Kemungkinan password corruption atau edge case)
            if ($student->password && Hash::check($request->nim, $student->password)) {
                Auth::guard('student')->login($student);
                return redirect()->intended(route('public.catalog'))
                    ->with('success', 'Login berhasil!');
            }
        }
        throw ValidationException::withMessages([
            'email' => ['Kombinasi Email dan NIM tidak cocok.'],
        ]);
    }

    /**
     * Handle Password Update
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::guard('student')->user();

        // Verify Current Password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.'],
            ]);
        }

        // Update Password
        // Note: We need to make sure we are updating the PeminjamUser model
        // The user object retrieved from Auth guard should be an instance of PeminjamUser
        // BUT we need to call save on it.

        $user->forceFill([
            'password' => Hash::make($request->new_password)
        ])->save();

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Handle Student Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.catalog')->with('info', 'Anda telah logout.');
    }
}
