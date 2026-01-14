<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use App\Models\Borrowing; // ✅ Import Borrowing
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLoanController extends Controller
{
    /**
     * Show Pending Requests
     */
    public function indexPending()
    {
        $loans = Loan::with(['user', 'item'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.loans.pending', compact('loans'));
    }

    /**
     * Approve Loan Request
     */
    public function approve(Request $request, $id)
    {
        // Gunakan Transaction untuk Data Integrity
        DB::transaction(function () use ($id) {

            // 1. Lock Row Loan & Item agar tidak race condition
            $loan = Loan::where('id', $id)->lockForUpdate()->firstOrFail();
            $item = Item::where('id', $loan->item_id)->lockForUpdate()->firstOrFail();

            if ($loan->status !== 'pending') {
                throw new \Exception('Status peminjaman sudah bukan pending.');
            }

            // 2. Cek Stok Lagi (Double Check)
            if ($item->quantity < $loan->quantity) {
                throw new \Exception('Stok barang tidak cukup saat ini.');
            }

            // 3. Update Status Loan
            $loan->update([
                'status' => 'active',
                'admin_note' => 'Approved by Admin',
            ]);

            // 4. Create Real Borrowing Record
            // Ini agar masuk ke daftar "Peminjaman Aktif" (BorrowingController)
            Borrowing::create([
                'user_id' => $loan->user_id,
                'item_id' => $loan->item_id,
                'quantity' => $loan->quantity, // Pastikan tabel borrowings punya kolom quantity
                'borrow_date' => $loan->borrow_date,
                'return_date' => $loan->return_date,
                'status' => 'borrowed', // Status 'borrowed' sesuai enum di Borrowing table
                'notes' => $loan->purpose,
            ]);

            // 5. Kurangi Stok Barang
            $item->decrement('quantity', $loan->quantity);

            // Jika stok habis, ubah status item jadi borrowed (jika item unik/aset)
            if ($item->fresh()->quantity == 0) {
                $item->update(['status' => 'borrowed']);
            }
        });

        return redirect()->route('admin.loans.pending')
            ->with('success', 'Permintaan peminjaman berhasil di-APPROVE dan masuk ke Peminjaman Aktif.');
    }

    /**
     * Reject Loan Request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string'
        ]);

        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya.');
        }

        $loan->update([
            'status' => 'rejected',
            'admin_note' => $request->input('reason', 'Ditolak oleh Admin'),
        ]);

        // Stok barang TIDAK disentuh

        return redirect()->route('admin.loans.pending')
            ->with('success', 'Permintaan peminjaman berhasil di-TOLAK.');
    }
}
