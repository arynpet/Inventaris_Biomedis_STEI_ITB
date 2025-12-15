<?php

namespace App\Http\Controllers;

use App\Models\PeminjamUser;
use Illuminate\Http\Request;

class PeminjamUserController extends Controller
{
    // List
    public function index()
    {
        $users = PeminjamUser::orderBy('name')->paginate(10);
        return view('peminjam-users.index', compact('users'));
    }

    // Create Form
    public function create()
    {
        return view('peminjam-users.create');
    }

    // Store
public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'nim'         => 'nullable|string|max:50',
        'email'       => 'nullable|email|max:255',
        'phone'       => 'nullable|string|max:20',
        'role'        => 'nullable|string|max:50',
        'is_trained'  => 'nullable|boolean',
    ]);

    PeminjamUser::create([
        'name'       => $request->name,
        'nim'        => $request->nim,
        'email'      => $request->email,
        'phone'      => $request->phone,
        'role'       => $request->role,
        'is_trained' => $request->is_trained ?? 0,
    ]);

    return redirect()->route('peminjam-users.index')
                     ->with('success', 'Data peminjam berhasil ditambahkan!');
}


// Edit Form
public function edit($id)
{
    $user = PeminjamUser::findOrFail($id);
    return view('peminjam-users.edit', compact('user'));
}


// Update
public function update(Request $request, $id)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'nim'         => 'nullable|string|max:50',
        'email'       => 'nullable|email|max:255',
        'phone'       => 'nullable|string|max:20',
        'role'        => 'nullable|string|max:50',
        'is_trained'  => 'nullable|boolean',
    ]);

    $user = PeminjamUser::findOrFail($id);

    $user->update([
        'name'       => $request->name,
        'nim'        => $request->nim,
        'email'      => $request->email,
        'phone'      => $request->phone,
        'role'       => $request->role,
        'is_trained' => $request->is_trained ?? 0,
    ]);

    return redirect()->route('peminjam-users.index')
                     ->with('success', 'Data peminjam berhasil diupdate!');
}


    // Delete
    public function destroy($id)
    {
        PeminjamUser::destroy($id);

        return redirect()->route('peminjam-users.index')
                         ->with('success', 'Data peminjam berhasil dihapus!');
    }
}
