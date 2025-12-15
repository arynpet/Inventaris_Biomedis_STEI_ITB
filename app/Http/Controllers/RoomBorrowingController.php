<?php

namespace App\Http\Controllers;

use App\Models\RoomBorrowing;
use App\Models\Room;
use App\Models\PeminjamUser;
use Illuminate\Http\Request;

class RoomBorrowingController extends Controller
{
    public function index()
    {
        $borrowings = RoomBorrowing::with('room', 'user')->latest()->paginate(10);
        return view('room_borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $rooms = Room::all();
        $users = PeminjamUser::all();

        return view('room_borrowings.create', compact('rooms', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'    => 'required',
            'user_id'    => 'required',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        RoomBorrowing::create($request->all());

        return redirect()->route('room_borrowings.index')
                         ->with('success', 'Peminjaman ruangan berhasil dibuat.');
    }

    public function show(RoomBorrowing $roomBorrowing)
    {
        return view('room_borrowings.show', compact('roomBorrowing'));
    }

    public function edit(RoomBorrowing $roomBorrowing)
    {
        $rooms = Room::all();
        $users = PeminjamUser::all();

        return view('room_borrowings.edit', compact('roomBorrowing', 'rooms', 'users'));
    }

    public function update(Request $request, RoomBorrowing $roomBorrowing)
    {
        $request->validate([
            'room_id'    => 'required',
            'user_id'    => 'required',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $roomBorrowing->update($request->all());

        return redirect()->route('room_borrowings.index')
                         ->with('success', 'Data peminjaman ruangan berhasil diperbarui.');
    }

    public function destroy(RoomBorrowing $roomBorrowing)
    {
        $roomBorrowing->delete();

        return redirect()->route('room_borrowings.index')
                         ->with('success', 'Data peminjaman ruangan berhasil dihapus.');
    }
}
