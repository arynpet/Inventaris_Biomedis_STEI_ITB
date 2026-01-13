<?php

namespace App\Http\Controllers;

use App\Models\PeminjamUser;
use Illuminate\Http\Request;

class PeminjamUserController extends Controller
{
    // List
    public function index(Request $request)
    {
        $query = PeminjamUser::query();

        // 1. Logic Search (Nama, NIM, Email)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 2. Logic Filter Role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // 3. Logic Filter Status Pelatihan
        if ($request->has('is_trained') && $request->is_trained != '') {
            $query->where('is_trained', $request->is_trained);
        }

        // 4. Urutkan dan Pagination
        if ($request->get('show_all') == '1') {
            $users = $query->latest()->get();
        } else {
            $users = $query->latest()->paginate(10)->withQueryString();
        }

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
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:50',
            'is_trained' => 'nullable|boolean',
        ]);

        PeminjamUser::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
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
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:50',
            'is_trained' => 'nullable|boolean',
        ]);

        $user = PeminjamUser::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'is_trained' => $request->is_trained ?? 0,
        ]);

        return redirect()->route('peminjam-users.index')
            ->with('success', 'Data peminjam berhasil diupdate!');
    }


    // Delete
    public function destroy($id)
    {
        // 1. Handle Room Borrowings (Cleanup Files)
        $roomBorrowings = \App\Models\RoomBorrowing::where('user_id', $id)->get();
        foreach ($roomBorrowings as $rb) {
            if ($rb->surat_peminjaman && \Illuminate\Support\Facades\Storage::disk('public')->exists($rb->surat_peminjaman)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($rb->surat_peminjaman);
            }
            $rb->delete();
        }

        // 2. Handle Item Borrowings
        \App\Models\Borrowing::where('user_id', $id)->delete();

        // 3. Delete User
        PeminjamUser::destroy($id);

        return redirect()->route('peminjam-users.index')
            ->with('success', 'Data peminjam berhasil dihapus!');
    }

    /**
     * Bulk Action
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action_type');
        $ids = $request->input('selected_ids', []);

        if (empty($ids))
            return back()->with('error', 'Tidak ada peminjam dipilih.');

        if ($action === 'delete') {
            // 1. Ambil RoomBorrowings terkait untuk hapus file
            $roomBorrowings = \App\Models\RoomBorrowing::whereIn('user_id', $ids)->get();
            foreach ($roomBorrowings as $rb) {
                if ($rb->surat_peminjaman && \Illuminate\Support\Facades\Storage::disk('public')->exists($rb->surat_peminjaman)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($rb->surat_peminjaman);
                }
                $rb->delete();
            }

            // 2. Hapus Item Borrowings
            \App\Models\Borrowing::whereIn('user_id', $ids)->delete();

            // 3. Hapus User
            PeminjamUser::whereIn('id', $ids)->delete();

            return back()->with('success', count($ids) . ' data peminjam berhasil dihapus.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
