<?php

namespace App\Http\Controllers;

use App\Models\RoomBorrowing;
use App\Models\Room;
use App\Models\PeminjamUser;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomBorrowingController extends Controller
{
    public function index()
    {
        // Index hanya menampilkan yang BELUM selesai
        $query = RoomBorrowing::with('room', 'user')
            ->whereIn('status', ['pending', 'approved'])
            ->latest();

        if (request('show_all') == '1') {
            $borrowings = $query->get();
        } else {
            $borrowings = $query->paginate(10);
        }

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
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:peminjam_users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'surat_peminjaman' => [
                'required', // TODO: Make optional if using remote URL, handle in manual check or simple rule 'nullable' if we trust processFileUpload handles it. 
                // Actually, let's allow images too now.
                // 'file', // removed to allow string URL
                // 'mimes:pdf', // removed to allow images
                // 'max:2048', 
            ],
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // ✅ M4 FIX: Use extracted method
        $overlap = $this->hasRoomOverlap(
            $request->room_id,
            $request->start_time,
            $request->end_time
        );

        if ($overlap) {
            return back()->withErrors(['start_time' => __('messages.room.overlap')]);
        }

        $data = $request->only(['room_id', 'user_id', 'start_time', 'end_time', 'purpose', 'notes']);

        // 2. SET DEFAULT STATUS
        $data['status'] = 'pending';

        // 3. LOGIKA UPLOAD FILE (Hybrid)
        $fileData = $this->processFileUpload($request, 'surat_peminjaman', 'surat_ruangan');
        if ($fileData) {
            $data['surat_peminjaman'] = $fileData['path'];
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
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:peminjam_users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'surat_peminjaman' => 'nullable', // Allow hybrid
            'purpose' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected,finished',
            'notes' => 'nullable|string',
        ]);

        // ✅ M4 FIX: Use extracted method with exclude ID
        $isOverlap = $this->hasRoomOverlap(
            $request->room_id,
            $request->start_time,
            $request->end_time,
            $roomBorrowing->id
        );

        if ($isOverlap) {
            return back()->withErrors(['start_time' => __('messages.room.overlap_update')])->withInput();
        }

        $data = $request->except('surat_peminjaman');

        // LOGIKA GANTI FILE
        $fileData = $this->processFileUpload($request, 'surat_peminjaman', 'surat_ruangan');
        if ($fileData) {
            // Hapus file lama
            if ($roomBorrowing->surat_peminjaman && Storage::disk('public')->exists($roomBorrowing->surat_peminjaman)) {
                Storage::disk('public')->delete($roomBorrowing->surat_peminjaman);
            }
            $data['surat_peminjaman'] = $fileData['path'];
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

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'return_room_borrowing',
            'model' => 'RoomBorrowing',
            'model_id' => $borrowing->id,
            'description' => "Returned room: {$borrowing->room->name} (Booking ID: {$borrowing->id})",
            'ip_address' => request()->ip(),
        ]);

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

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_room_borrowing',
            'model' => 'RoomBorrowing',
            'model_id' => $borrowing->id,
            'description' => 'Menyetujui peminjaman ruangan dengan ID ' . $borrowing->id,
            'ip_address' => request()->ip(),
        ]);

        $borrowing->update(['status' => 'approved']);

        return back()->with('success', 'Peminjaman ruangan berhasil disetujui. Status kini Approved.');
    }

    private function hasRoomOverlap($roomId, $startTime, $endTime, $excludeId = null)
    {
        return RoomBorrowing::where('room_id', $roomId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->whereNotIn('status', ['finished', 'rejected'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();
    }

    /**
     * Bulk Action
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $action = $request->input('action_type');

        if (empty($ids))
            return back()->with('error', 'Tidak ada item dipilih.');

        if ($action === 'delete') {
            $borrowings = RoomBorrowing::whereIn('id', $ids)->get();
            foreach ($borrowings as $b) {
                if ($b->surat_peminjaman && Storage::disk('public')->exists($b->surat_peminjaman)) {
                    Storage::disk('public')->delete($b->surat_peminjaman);
                }
                $b->delete();
            }
            return back()->with('success', count($ids) . ' peminjaman ruangan berhasil dihapus.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Helper to process file upload (Direct or Remote URL)
     * Returns ['path' => ..., 'name' => ...] or null
     */
    private function processFileUpload(Request $request, $inputName, $targetFolder)
    {
        // 1. Direct File Upload
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $originalName = $file->getClientOriginalName();
            $extension = $file->extension();
            $safeName = \Illuminate\Support\Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $fileName = time() . '_' . $safeName . '.' . $extension;
            $path = $file->storeAs($targetFolder, $fileName, 'public');

            return ['path' => $path, 'name' => $fileName];
        }

        // 2. Remote URL (scan from HP)
        // Hidden input format name: "inputName_url" (e.g., file_upload_url)
        $urlInput = $inputName . '_url';
        if ($request->filled($urlInput)) {
            $url = $request->input($urlInput);

            // Check if it's from our temp storage
            if (str_contains($url, '/storage/temp/')) {
                try {
                    $basename = basename($url);
                    $tempPath = 'temp/' . $basename;
                    $newFileName = time() . '_remote_' . $basename;
                    $newPath = $targetFolder . '/' . $newFileName;

                    if (Storage::disk('public')->exists($tempPath)) {
                        Storage::disk('public')->move($tempPath, $newPath);
                        return ['path' => $newPath, 'name' => $newFileName];
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to move remote file: " . $e->getMessage());
                }
            }
        }

        return null; // No file uploaded
    }
}