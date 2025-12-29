<?php

namespace App\Http\Controllers;

use App\Models\RoomBorrowing;
use App\Models\Room;
use App\Models\PeminjamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomBorrowingController extends Controller
{
public function index()
{
    // Index hanya menampilkan yang BELUM selesai
    $borrowings = RoomBorrowing::with('room', 'user')
        ->whereIn('status', ['pending', 'approved']) 
        ->latest()
        ->paginate(10);
        
    return view('room_borrowings.index', compact('borrowings'));
}

    public function create()
    {
        $rooms = Room::where('status', 'sedia')->get();
        $users = PeminjamUser::orderBy('name')->get();

        return view('room_borrowings.create', compact('rooms', 'users'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI DIPERLENGKAP
        // ✅ H5 FIX: Enhanced file validation with actual MIME type check
        $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'user_id'          => 'required|exists:peminjam_users,id',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'surat_peminjaman' => [
                'required',
                'file',
                'mimes:pdf',
                'max:2048',
                function($attribute, $value, $fail) {
                    if ($value->getMimeType() !== 'application/pdf') {
                        $fail('File harus PDF valid (bukan hanya extension .pdf).');
                    }
                }
            ],
            
            // Tambahan Validasi agar data tidak kosong/error
            'purpose'          => 'required|string|max:255', 
            'notes'            => 'nullable|string',
        ]);

        // ✅ H1 FIX: Check for room booking conflicts
        $overlap = RoomBorrowing::where('room_id', $request->room_id)
            ->where(function($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->whereNotIn('status', ['finished', 'rejected'])
            ->exists();

        if ($overlap) {
            return back()->withErrors(['start_time' => 'Ruangan sudah dipesan di waktu tersebut! Silakan pilih waktu lain.']);
        }

        $data = $request->only(['room_id', 'user_id', 'start_time', 'end_time', 'purpose', 'notes']);

        // 2. SET DEFAULT STATUS
        // Karena di form create biasanya tidak ada input status, kita set default 'pending'
        $data['status'] = 'pending'; 

        // 3. LOGIKA UPLOAD FILE
        if ($request->hasFile('surat_peminjaman')) {
            $filePath = $request->file('surat_peminjaman')->store('surat_ruangan', 'public');
            $data['surat_peminjaman'] = $filePath;
        }

        RoomBorrowing::create($data);

        return redirect()->route('room_borrowings.index')
            ->with('success', 'Peminjaman berhasil diajukan! Status menunggu persetujuan.');
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
            'room_id'          => 'required|exists:rooms,id',
            'user_id'          => 'required|exists:peminjam_users,id',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'surat_peminjaman' => 'nullable|file|mimes:pdf|max:2048',
            
            // Tambahan Validasi Update
            'purpose'          => 'required|string|max:255',
            'status'           => 'required|in:pending,approved,rejected,finished', // Status bisa diubah saat edit
            'notes'            => 'nullable|string',
        ]);

        $data = $request->except('surat_peminjaman');

        // LOGIKA GANTI FILE
        if ($request->hasFile('surat_peminjaman')) {
            if ($roomBorrowing->surat_peminjaman && Storage::disk('public')->exists($roomBorrowing->surat_peminjaman)) {
                Storage::disk('public')->delete($roomBorrowing->surat_peminjaman);
            }
            $filePath = $request->file('surat_peminjaman')->store('surat_ruangan', 'public');
            $data['surat_peminjaman'] = $filePath;
        }

        $roomBorrowing->update($data);

        return redirect()->route('room_borrowings.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy(RoomBorrowing $roomBorrowing)
    {
        if ($roomBorrowing->surat_peminjaman && Storage::disk('public')->exists($roomBorrowing->surat_peminjaman)) {
            Storage::disk('public')->delete($roomBorrowing->surat_peminjaman);
        }

        $roomBorrowing->delete();

        return redirect()->route('room_borrowings.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // ... method lainnya ...

    /**
     * Aksi Mengembalikan Ruangan (Selesaikan Peminjaman)
     */
    public function returnRoom($id)
    {
        $borrowing = RoomBorrowing::findOrFail($id);

        // Validasi sederhana
        if ($borrowing->status === 'finished') {
            return back()->with('error', 'Peminjaman ini sudah selesai sebelumnya.');
        }

        // Update status menjadi finished
        $borrowing->update([
            'status' => 'finished'
        ]);

        return back()->with('success', 'Ruangan berhasil dikembalikan dan status peminjaman selesai.');
    }

    /**
     * Halaman History (Peminjaman yang sudah selesai/ditolak)
     */
    public function history()
    {
        // Ambil data yang statusnya 'finished' atau 'rejected'
        $histories = RoomBorrowing::with(['room', 'user'])
            ->whereIn('status', ['finished', 'rejected'])
            ->latest()
            ->paginate(10);

        return view('room_borrowings.history', compact('histories'));
    }

    /**
     * Setujui Peminjaman (Pending -> Approved)
     */
    public function approveRoom($id)
    {
        $borrowing = RoomBorrowing::findOrFail($id);

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman berstatus Pending yang bisa disetujui.');
        }

        $borrowing->update(['status' => 'approved']);

        return back()->with('success', 'Peminjaman ruangan berhasil disetujui. Status kini Approved.');
    }
    
    // ... method lainnya ...
}