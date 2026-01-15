<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use App\Models\Borrowing; // ✅ Import Borrowing
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    // approve() method
    public function approve(Request $request, $id)
    {
        $loan = null;
        $item = null;
        
        // Transaction untuk data integrity
        DB::transaction(function () use ($id, &$loan, &$item) {
            $loan = Loan::where('id', $id)->lockForUpdate()->firstOrFail();
            $item = Item::where('id', $loan->item_id)->lockForUpdate()->firstOrFail();
            
            if ($loan->status !== 'pending') {
                throw new \Exception('Status peminjaman sudah bukan pending.');
            }
            
            if ($item->quantity < $loan->quantity) {
                throw new \Exception('Stok barang tidak cukup saat ini.');
            }
            
            $loan->update([
                'status' => 'active',
                'admin_note' => 'Approved by Admin',
            ]);
            
            Borrowing::create([
                'user_id' => $loan->user_id,
                'item_id' => $loan->item_id,
                'quantity' => $loan->quantity,
                'borrow_date' => $loan->borrow_date,
                'return_date' => $loan->return_date,
                'status' => 'borrowed',
                'notes' => $loan->purpose,
            ]);
            
            $item->decrement('quantity', $loan->quantity);
            
            if ($item->fresh()->quantity == 0) {
                $item->update(['status' => 'borrowed']);
            }
        });
        
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_loan',
            'model' => 'Loan',
            'model_id' => $loan->id,
            'description' => "Approved loan request ID {$loan->id} for {$loan->user->name} - Item: {$item->name} (Qty: {$loan->quantity})",
            'ip_address' => request()->ip(),
        ]);
        
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
        
        $loan = Loan::with('user')->findOrFail($id);
        
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya.');
        }
        
        $loan->update([
            'status' => 'rejected',
            'admin_note' => $request->input('reason', 'Ditolak oleh Admin'),
        ]);
        
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject_loan',
            'model' => 'Loan',
            'model_id' => $loan->id,
            'description' => "Rejected loan request ID {$loan->id} for {$loan->user->name}. Reason: " . ($request->input('reason') ?? 'No reason provided'),
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.loans.pending')
            ->with('success', 'Permintaan peminjaman berhasil di-TOLAK.');
    }
}
