<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:admin,superadmin'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Logic Khusus jika membuat Super Admin
        if ($request->role === 'superadmin') {

            // Validasi Input Password Verifikasi Harus Ada
            $request->validate([
                'superadmin_verification' => 'required',
            ], [
                'superadmin_verification.required' => 'Untuk membuat Super Admin, Anda wajib memasukkan password Anda sendiri.'
            ]);

            // Cek apakah password yang dimasukkan cocok dengan password user yang sedang login
            if (!Hash::check($request->superadmin_verification, auth()->user()->password)) {
                return back()
                    ->withInput() // Kembalikan input sebelumnya biar gak ngetik ulang
                    ->withErrors(['superadmin_verification' => 'Password verifikasi salah! Gagal membuat Super Admin.']);
            }
        }

        // 3. Simpan User Baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Bulk Action
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $action = $request->input('action_type');

        if (empty($ids))
            return back()->with('error', 'Tidak ada user yang dipilih.');

        if ($action === 'delete') {
            // Filter ID sendiri
            $ids = array_filter($ids, function ($id) {
                return $id != auth()->id();
            });

            if (empty($ids)) {
                return back()->with('error', 'Semua user yang dipilih tidak valid untuk dihapus (termasuk akun Anda sendiri).');
            }

            User::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' user berhasil dihapus.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}