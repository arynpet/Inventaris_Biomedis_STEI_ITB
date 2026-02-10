<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ItemPackage;
use App\Models\Borrowing;
use App\Models\PeminjamUser;
use App\Models\Item;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PracticumBorrowingController extends Controller
{
    public function index()
    {
        // Simple listing for now - we might want to list "Packages Currently Borrowed"
        // Since we don't have a 'package_borrowings' table, we can list borrowings that are linked to items in a package?
        // Or better, just show a page to START a borrowing session.
        // Let's list ItemPackages that are AVAILABLE.

        $packages = ItemPackage::withCount(['items'])->get();
        return view('practicum_borrowings.index', compact('packages'));
    }

    public function create()
    {
        // Not used directly, we select package from index
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_package_id' => 'required|exists:item_packages,id',
            'user_id' => 'required|exists:peminjam_users,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
        ]);

        $package = ItemPackage::with('items')->findOrFail($request->item_package_id);

        if ($package->items->isEmpty()) {
            return back()->with('error', 'Paket ini kosong, tidak ada item yang bisa dipinjam.');
        }

        // Check availability of ALL items in the package
        foreach ($package->items as $item) {
            if ($item->status !== 'available') {
                return back()->with('error', "Item '{$item->name}' dalam paket sedang tidak tersedia (Status: {$item->status}).");
            }
        }

        DB::transaction(function () use ($request, $package) {
            foreach ($package->items as $item) {
                // Create Borrowing Record
                Borrowing::create([
                    'item_id' => $item->id,
                    'user_id' => $request->user_id,
                    'quantity' => 1,
                    'borrow_date' => $request->borrow_date,
                    'return_date' => $request->return_date,
                    'status' => 'borrowed',
                    'notes' => $request->notes . ' [Paket: ' . $package->name . ']',
                    // We might want to link them via a batch_id if we added migration, but for now simple borrowing is enough
                ]);

                // Update Item Status
                $item->update(['status' => 'borrowed']);
            }

            // Optionally mark package as borrowed?
            // $package->update(['status' => 'borrowed']); 
        });

        return redirect()->route('borrowings.index')->with('success', "Peminjaman Paket '{$package->name}' berhasil dicatat.");
    }
}
