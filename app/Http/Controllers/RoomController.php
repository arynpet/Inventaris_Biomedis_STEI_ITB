<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Tambahkan ini untuk generate random string saat duplikat

class RoomController extends Controller
{
    /**
     * Tampilkan daftar ruangan dengan Filter, Search, dan Sort
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter
        $search = $request->input('search');
        $status = $request->input('status');
        
        // 2. Query Builder
        $rooms = Room::query()
            ->when($search, function ($query, $search) {
                // Cari berdasarkan Nama atau Kode Ruangan
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            // Default sort by Code ascending, tapi bisa diubah jika nanti mau fitur sort header
            ->orderBy('code', 'asc') 
            ->paginate(10) // Pagination 10 baris per halaman
            ->withQueryString(); // Agar filter tidak hilang saat ganti halaman

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Fitur Baru: Bulk Action (Hapus Banyak / Duplikat Banyak)
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action_type'); // 'delete' atau 'copy'
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada ruangan yang dipilih.');
        }

        try {
            $result = DB::transaction(function () use ($ids, $action) {
                if ($action === 'delete') {
                    // Hapus Masal
                    Room::whereIn('id', $ids)->delete();
                    return ['success' => true, 'count' => count($ids), 'action' => 'delete'];
                
                } elseif ($action === 'copy') {
                    // Duplikat Masal
                    $count = 0;
                    foreach ($ids as $id) {
                        $original = Room::find($id);
                        if ($original) {
                            $newRoom = $original->replicate();
                            // Kode harus unik, jadi kita tambahkan suffix random
                            $newRoom->code = $original->code . '-CPY-' . strtoupper(Str::random(3));
                            $newRoom->name = $original->name . ' (Copy)';
                            $newRoom->save();
                            $count++;
                        }
                    }
                    return ['success' => true, 'count' => $count, 'action' => 'copy'];
                }
                
                return ['success' => false];
            });

            if ($result['success']) {
                if ($result['action'] === 'delete') {
                    return redirect()->back()->with('success', $result['count'] . ' ruangan berhasil dihapus.');
                } elseif ($result['action'] === 'copy') {
                    return redirect()->back()->with('success', $result['count'] . ' ruangan berhasil diduplikasi.');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }

        return redirect()->back()->with('error', 'Aksi tidak dikenali.');
    }

    /**
     * Form tambah ruangan
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Simpan ruangan baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|unique:rooms,code|max:20',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:sedia,dipinjam',
            ]);

            Room::create($validated);

            return redirect()
                ->route('rooms.index')
                ->with('success', 'Ruangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah ruangan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit ruangan
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update ruangan
     */
    public function update(Request $request, Room $room)
    {
        try {
            $validated = $request->validate([
                'code' => "required|string|max:20|unique:rooms,code,$room->id",
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:sedia,dipinjam',
            ]);

            $room->update($validated);

            return redirect()
                ->route('rooms.index')
                ->with('success', 'Ruangan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update ruangan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete ruangan (Single)
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }

    /**
     * Detail Ruangan
     */
    public function show(Room $room)
    {
        $room->load('items'); // Ambil barang di ruangan ini

        // Ambil daftar semua ruangan lain untuk dropdown pemindahan (kecuali ruangan ini sendiri)
        $rooms = Room::where('id', '!=', $room->id)->orderBy('name')->get();

        return view('rooms.show', compact('room', 'rooms'));
    }

    /**
     * Pindahkan Item antar ruangan
     */
    public function moveItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'new_room_id' => 'required|exists:rooms,id',
        ]);

        $item = Item::findOrFail($request->item_id);

        $item->room_id = $request->new_room_id;
        $item->save();

        return back()->with('success', 'Barang berhasil dipindahkan.');
    }
}