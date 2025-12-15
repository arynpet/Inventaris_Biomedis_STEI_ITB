<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Item;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Tampilkan daftar ruangan
     */
    public function index()
    {
        $rooms = Room::orderBy('code')->get();
        return view('rooms.index', compact('rooms'));
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
        $validated = $request->validate([
            'code' => 'required|string|unique:rooms',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:sedia,dipinjam',
        ]);

        Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan!');
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
        $validated = $request->validate([
            'code' => "required|string|unique:rooms,code,$room->id",
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:sedia,dipinjam',
        ]);

        $room->update($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui!');
    }

    /**
     * Delete ruangan
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }

    public function show(Room $room)
{
    $room->load('items'); // Ambil barang di ruangan ini

    // Ambil daftar semua ruangan untuk dropdown pemindahan
    $rooms = Room::orderBy('name')->get();

    return view('rooms.show', compact('room', 'rooms'));
}

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
