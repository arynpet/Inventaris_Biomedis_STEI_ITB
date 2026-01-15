<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class LoanRequestController extends Controller
{
    /**
     * Display Student's Loan History
     */
    public function index()
    {
        $loans = Loan::with('item')
            ->where('user_id', Auth::guard('student')->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.loans.index', compact('loans'));
    }

    /**
     * Store a new loan request
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'purpose' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $item = Item::where('id', $request->item_id)->lockForUpdate()->firstOrFail();

            // 2. Cek Stok Barang (JANGAN Check Out Dulu)
            if ($item->quantity < $request->quantity) {
                throw ValidationException::withMessages([
                'quantity' => ['Stok barang tidak mencukupi. Tersedia: ' . $item->quantity],
                ]);
            }

            // 3. Cek apakah barang sedang maintenance/rusak
            if ($item->status !== 'available' && $item->status !== 'borrowed') {
                // 'borrowed' status on Item usually means fully borrowed, but here we depend on quantity check mainly.
                // If item status is specifically 'maintenance' or 'broken', we should block.
                throw ValidationException::withMessages([
                    'item_id' => ['Barang sedang tidak tersedia (Maintenance/Rusak).'],
                ]);
            }

            // 4. Buat Request Pinjaman (Status Pending)
            Loan::create([
                'user_id' => Auth::guard('student')->id(),
                'item_id' => $item->id,
                'quantity' => $request->quantity,
                'purpose' => $request->purpose,
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('student.loans.index')
            ->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan Admin.');
    }
}
