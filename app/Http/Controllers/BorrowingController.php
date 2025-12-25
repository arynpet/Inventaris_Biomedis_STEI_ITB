<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\PeminjamUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BorrowingController extends Controller
{
    // ==========================================
    // INDEX
    // ==========================================
    public function index()
    {
        $borrowings = Borrowing::with(['item', 'borrower']) 
            ->where('status', 'borrowed') 
            ->latest()
            ->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    // ==========================================
    // CREATE FORM
    // ==========================================
    public function create()
    {
        $items = Item::orderBy('name')->where('status', 'available')->get(); // Filter hanya yg available
        $users = PeminjamUser::orderBy('name')->get();

        return view('borrowings.create', compact('items', 'users'));
    }

    // ==========================================
    // STORE (Proses Pinjam)
    // ==========================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:peminjam_users,id',
            'item_id'       => 'required|exists:items,id',
            'borrow_date'   => 'required|date',
            'return_date'   => 'nullable|date|after_or_equal:borrow_date',
            'notes'         => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);
  
        // Double check status barang
        if ($item->status !== 'available') {
            return back()->withErrors(['item_id' => 'Item sedang dipinjam atau dalam perbaikan.']);
        }

        // Buat Peminjaman
        Borrowing::create([
            'item_id'     => $validated['item_id'],
            'user_id'     => $validated['user_id'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'notes'       => $validated['notes'],
            'status'      => 'borrowed',
        ]);
  
        // Update Status Item
        $item->update(['status' => 'borrowed']);
  
        return redirect()->route('borrowings.index')
                         ->with('success', 'Peminjaman berhasil dibuat!');
    }

    // ==========================================
    // EDIT & UPDATE (Admin Correction)
    // ==========================================
    public function edit(Borrowing $borrowing)
    {
        $items = Item::orderBy('name')->get();
        $users = PeminjamUser::orderBy('name')->get();

        return view('borrowings.edit', compact('borrowing', 'items', 'users'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:peminjam_users,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
            'status' => 'required|in:borrowed,returned,late',
            'notes' => 'nullable|string',
        ]);

        $borrowing->update($request->all());

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // ==========================================
    // DESTROY
    // ==========================================
    public function destroy(Borrowing $borrowing)
    {
        // Kembalikan status item jadi available jika peminjaman dihapus (opsional, tergantung kebijakan)
        if($borrowing->status == 'borrowed') {
            $borrowing->item()->update(['status' => 'available']);
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['item.room', 'borrower']);
        return view('borrowings.show', ['borrow' => $borrowing]);
    }

    // ==========================================
    // RETURN (PENGEMBALIAN BARANG) - REVISI
    // ==========================================
    // Method ini sekarang menerima Request untuk menangkap input kondisi
    public function returnItem(Request $request, $id)
    {
        $validated = $request->validate([
            'condition' => 'required|in:good,damaged,broken',
        ]);

        $borrow = Borrowing::with('item')->findOrFail($id); 

        if ($borrow->status === 'returned') {
            return back()->withErrors(['error' => 'Barang sudah dikembalikan sebelumnya.']);
        }

        DB::transaction(function () use ($borrow, $validated) {
            $condition = $validated['condition'];
            
            // A. Update Peminjaman
            $borrow->update([
                'status'           => 'returned',
                'return_date'      => now(),
                'return_condition' => $condition,
            ]);

            $newStatus = ($condition === 'good') ? 'available' : 'maintenance';
            
            $borrow->item->update([
                'status'    => $newStatus,
                'condition' => $condition
            ]);
        });

        return back()->with('success', 'Barang berhasil dikembalikan dengan kondisi: ' . $validated['condition']);
    }

    // ==========================================
    // HISTORY & REPORT
    // ==========================================
    public function history(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $query = Borrowing::with(['item', 'borrower'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc');

        if ($from) {
            $query->whereDate('borrow_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('return_date', '<=', $to);
        }

        $history = $query->get();

        return view('borrowings.history', compact('history', 'from', 'to'));
    }

    public function historyPdf(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $query = Borrowing::with(['item', 'borrower'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc');

        if ($from) {
            $query->whereDate('borrow_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('return_date', '<=', $to);
        }

        $history = $query->get();

        $data = [
            'history'      => $history,
            'generated_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
            'from'         => $from,
            'to'           => $to,
        ];

        $pdf = Pdf::loadView('borrowings.history_pdf', $data)
                ->setPaper('a4', 'portrait');

        return $pdf->stream('history.pdf');
    }

    // ==========================================
    // API / AJAX SCANNER
    // ==========================================
    public function findItemByQr(Request $request)
    {
        $request->validate(['qr' => 'required|string']);

        $item = Item::where('serial_number', $request->qr)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        if ($item->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'Item sedang tidak tersedia (Status: '.$item->status.')'], 400);
        }

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
            ]
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate(['qr' => 'required|string']);

        $item = Item::where('serial_number', $request->qr)
            ->orWhere('qr_code', $request->qr)
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan']);
        }

        if ($item->status === 'borrowed') {
            return response()->json(['success' => false, 'message' => 'Item sedang dipinjam']);
        }

        return response()->json([
            'success' => true,
            'item' => [
                'id'   => $item->id,
                'name' => $item->name
            ]
        ]);
    }
}