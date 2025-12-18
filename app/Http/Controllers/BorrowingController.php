<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\PeminjamUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;



class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['item', 'borrower'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        $users = PeminjamUser::orderBy('name')->get();

        return view('borrowings.create', compact('items', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:peminjam_users,id',
            'item_id'       => 'required|exists:items,id',
            'borrow_date'   => 'required|date',
            'return_date'   => 'nullable|date|after_or_equal:borrow_date',
            'notes'         => 'nullable|string',
        ]);

        Borrowing::create([
            'item_id'   => $validated['item_id'],
            'user_id'   => $validated['user_id'],
            'borrow_date' => $validated['borrow_date'] = now(),
            'return_date' => $validated['return_date'],
            'notes'     => $validated['notes'],
            'status'    => 'borrowed',
        ]);

        return redirect()->route('borrowings.index')
                        ->with('success', 'Borrowing created successfully!');
    }

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

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function show(Borrowing $borrowing)
    {
        // Load relasi item dan user
        $borrowing->load(['item.room', 'borrower']);

        return view('borrowings.show', [
            'borrow' => $borrowing
        ]);
    }

    public function return($id)
    {
        $borrow = Borrowing::findOrFail($id);

        $borrow->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

            Item::where('id', $borrow->item_id)
            ->update(['status' => 'available']);

        return redirect()->back()->with('success', 'Barang berhasil dikembalikan!');
    }

    public function history(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $query = Borrowing::with(['item', 'borrower'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc');

        // Filtering
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
}
