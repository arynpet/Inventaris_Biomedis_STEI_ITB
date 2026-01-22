<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use App\Http\Requests\Loan\StoreLoanRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class LoanRequestController extends Controller
{
    /**
     * Display Student's Loan History (WITH EAGER LOADING - FIX N+1)
     */
    public function index()
    {
        // Eager load 'item' and nested 'room' to prevent N+1 queries
        $loans = Loan::with(['item.room', 'item.categories'])
            ->where('user_id', Auth::guard('student')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Use pagination for better performance

        return view('student.loans.index', compact('loans'));
    }

    /**
     * Store a new loan request (SECURE with Form Request)
     */
    public function store(StoreLoanRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Lock the item row to prevent race conditions
            $item = Item::where('id', $request->item_id)->lockForUpdate()->firstOrFail();

            // Check stock availability
            if ($item->quantity < $request->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ['Stok barang tidak mencukupi. Tersedia: ' . $item->quantity],
                ]);
            }

            // Check item status
            if ($item->status !== 'available' && $item->status !== 'borrowed') {
                throw ValidationException::withMessages([
                    'item_id' => ['Barang sedang tidak tersedia (Maintenance/Rusak).'],
                ]);
            }

            // Create loan request with SECURE data mapping
            $loan = Loan::create($request->safeLoanData());

            // LOG STUDENT ACTIVITY
            \App\Models\ActivityLog::create([
                'user_id' => auth('student')->id(),
                'action' => 'request_loan',
                'model' => 'Loan',
                'model_id' => $loan->id,
                'description' => "Student " . auth('student')->user()->name . " requested loan for item: {$item->name} (Qty: {$request->quantity})",
                'ip_address' => request()->ip(),
            ]);
        });

        return redirect()->route('student.loans.index')
            ->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan Admin.');
    }
}
