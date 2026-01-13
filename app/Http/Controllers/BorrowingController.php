<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\PeminjamUser;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

class BorrowingController extends Controller
{
    // ==========================================
    // INDEX (REVISI: Ditambahkan Search, Filter, Sort)
    // ==========================================
    public function index(Request $request)
    {
        // 1. Ambil Input Filter
        $search = $request->input('search');
        $sort = $request->input('direction', 'desc'); // Default desc (Terbaru)
        $status_filter = $request->input('status_filter'); // 'late' atau null

        // 2. Query Builder
        $borrowings = Borrowing::with(['item', 'borrower'])
            ->where('status', 'borrowed') // Hanya ambil yang SEDANG dipinjam

            // Logika Pencarian (Nama Peminjam ATAU Nama Barang)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('borrower', function ($b) use ($search) {
                        $b->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('item', function ($i) use ($search) {
                            $i->where('name', 'like', "%{$search}%");
                        });
                });
            })

            // Logika Filter Terlambat
            ->when($status_filter === 'late', function ($query) {
                return $query->whereDate('return_date', '<', now());
            })

            // Logika Sorting (Berdasarkan tanggal pinjam)
            ->orderBy('borrow_date', $sort);

        if ($request->get('show_all') == '1') {
            $borrowings = $borrowings->get();
        } else {
            $borrowings = $borrowings->paginate(10)->withQueryString();
        }

        return view('borrowings.index', compact('borrowings'));
    }

    // ==========================================
    // BULK RETURN (FITUR BARU)
    // ==========================================
    public function bulkReturn(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $condition = $request->input('condition', 'good'); // good, damaged, broken

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada peminjaman yang dipilih.');
        }

        // Gunakan Transaksi DB agar aman
        DB::transaction(function () use ($ids, $condition) {
            // Ambil data borrowing yang dipilih dan masih status borrowed
            // ✅ FIXED: Added lockForUpdate() to prevent race condition
            $borrowings = Borrowing::whereIn('id', $ids)
                ->where('status', 'borrowed')
                ->lockForUpdate()
                ->with('item')
                ->get();

            foreach ($borrowings as $borrow) {
                // 1. Update Peminjaman
                $borrow->update([
                    'status' => 'returned',
                    'return_date' => now(),
                    'return_condition' => $condition
                ]);

                // 2. Update Status Item
                // Logika ini MENGIKUTI logika returnItem() yang sudah ada:
                // Jika good -> available, Jika rusak -> maintenance
                $newItemStatus = ($condition === 'good') ? 'available' : 'maintenance';

                if ($borrow->item) {
                    $borrow->item->update([
                        'status' => $newItemStatus,
                        'condition' => $condition
                    ]);
                }
                
                // ✅ 3. LOG setiap borrowing yang di-return
                ActivityLog::create([
                    'user_id'   => auth()->id(),
                    'action'    => 'bulk_return',
                    'model' => 'Borrowing',
                    'model_id'   => $borrow->id,
                    'description' => "Returned '{$borrow->item->name}' (Condition: {$condition})",
                    'ip_address' => request()->ip(),
                ]);
            }
        });

        return back()->with('success', count($ids) . ' barang berhasil dikembalikan masal (Kondisi: ' . ucfirst($condition) . ').');
    }

    // ==========================================
    // CREATE FORM (TETAP SAMA)
    // ==========================================
    public function create()
    {
        $items = Item::orderBy('name')->where('status', 'available')->get(); // Filter hanya yg available
        $users = PeminjamUser::orderBy('name')->get();

        return view('borrowings.create', compact('items', 'users'));
    }

    // ==========================================
    // STORE (Proses Pinjam) (TETAP SAMA)
    // ==========================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:peminjam_users,id',
            'item_id' => 'required|exists:items,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $item = Item::where('id', $validated['item_id'])
                ->lockForUpdate()
                ->first();

            if ($item->status !== 'available') {
                throw ValidationException::withMessages([
                    'item_id' => 'Item sedang dipinjam atau dalam perbaikan.'
                ]);
            }

            Borrowing::create([
                'item_id' => $validated['item_id'],
                'user_id' => $validated['user_id'],
                'borrow_date' => $validated['borrow_date'],
                'return_date' => $validated['return_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'borrowed',
            ]);

            $item->update(['status' => 'borrowed']);
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dibuat!');
    }

    // ==========================================
    // EDIT & UPDATE (Admin Correction) (TETAP SAMA)
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

        DB::transaction(function () use ($request, $borrowing) {
            // Check if item changed and validate availability
            if ($request->item_id != $borrowing->item_id) {
                $newItem = Item::where('id', $request->item_id)
                    ->lockForUpdate()
                    ->first();

                if (!$newItem) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'item_id' => 'Item tidak ditemukan.'
                    ]);
                }

                if ($newItem->status !== 'available') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'item_id' => 'Item yang dipilih sedang tidak tersedia (Status: ' . $newItem->status . ').'
                    ]);
                }

                // Return old item to available only if borrowing is still active
                if ($borrowing->status === 'borrowed') {
                    $borrowing->item->update(['status' => 'available']);
                }

                // Update new item to borrowed
                $newItem->update(['status' => 'borrowed']);
            }

            $borrowing->update($request->only(['item_id', 'user_id', 'borrow_date', 'return_date', 'status', 'notes']));
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // ==========================================
    // DESTROY (TETAP SAMA)
    // ==========================================
    public function destroy(Borrowing $borrowing)
    {
        // Kembalikan status item jadi available jika peminjaman dihapus (opsional, tergantung kebijakan)
        if ($borrowing->status == 'borrowed') {
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
    // RETURN (SINGLE) (TETAP SAMA)
    // ==========================================
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
                'status' => 'returned',
                'return_date' => now(),
                'return_condition' => $condition,
            ]);

            $newStatus = ($condition === 'good') ? 'available' : 'maintenance';

            $borrow->item->update([
                'status' => $newStatus,
                'condition' => $condition
            ]);
            
            // ✅ B. LOG di dalam transaction
            ActivityLog::create([
                'user_id'   => auth()->id(),
                'action'    => 'return_item',
                'model' => 'Borrowing',
                'model_id'   => $borrow->id,
                'description' => "Returned '{$borrow->item->name}' (Condition: {$condition})",
                'ip_address' => request()->ip(),
            ]);
        });

        return back()->with('success', 'Barang berhasil dikembalikan dengan kondisi: ' . $validated['condition']);
    }

    // ==========================================
    // HISTORY & REPORT (TETAP SAMA)
    // ==========================================
    public function history(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

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
        $to = $request->query('to');

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
            'history' => $history,
            'generated_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
            'from' => $from,
            'to' => $to,
        ];

        $pdf = Pdf::loadView('borrowings.history_pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('history.pdf');
    }

    // ==========================================
    // API / AJAX SCANNER (TETAP SAMA)
    // ==========================================
    public function findItemByQr(Request $request)
    {
        $request->validate(['qr' => 'required|string']);

        $item = Item::where('serial_number', $request->qr)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        if ($item->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'Item sedang tidak tersedia (Status: ' . $item->status . ')'], 400);
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
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 200);
        }

        if ($item->status === 'borrowed') {
            return response()->json(['success' => false, 'message' => 'Item sedang dipinjam'], 200);
        }

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name
            ]
        ], 200);
    }
}