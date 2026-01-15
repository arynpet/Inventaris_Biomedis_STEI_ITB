<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenances.
     */
    public function index(Request $request)
    {
        $query = Maintenance::with(['item.room']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $maintenances = $query->orderBy('scheduled_date', 'desc')->paginate(15);

        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new maintenance.
     */
    public function create(Request $request)
    {
        $itemId = $request->query('item_id');
        $item = $itemId ? Item::findOrFail($itemId) : null;

        return view('maintenances.create', compact('item'));
    }

    /**
     * Store a newly created maintenance in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:calibration,repair,cleaning,inspection',
            'scheduled_date' => 'required|date',
            'cost' => 'nullable|integer|min:0',
            'technician_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        $validated['status'] = 'pending';

        $maintenance = Maintenance::create($validated);

        return redirect()->route('items.show', $maintenance->item_id)
            ->with('success', 'Jadwal maintenance berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified maintenance.
     */
    public function edit(Maintenance $maintenance)
    {
        return view('maintenances.edit', compact('maintenance'));
    }

    /**
     * Update the specified maintenance in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'type' => 'required|in:calibration,repair,cleaning,inspection',
            'scheduled_date' => 'required|date',
            'cost' => 'nullable|integer|min:0',
            'technician_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $maintenance->update($validated);

        return redirect()->route('items.show', $maintenance->item_id)
            ->with('success', 'Data maintenance berhasil diupdate.');
    }

    /**
     * Mark maintenance as in progress.
     * This will also set the item status to 'maintenance'.
     */
    public function start(Maintenance $maintenance)
    {
        DB::transaction(function () use ($maintenance) {
            // Update maintenance status
            $maintenance->update(['status' => 'in_progress']);

            // Update item status to prevent borrowing
            $maintenance->item->update(['status' => 'maintenance']);
        });

        return back()->with('info', 'Maintenance dimulai. Status barang diubah menjadi "Maintenance".');
    }

    /**
     * Mark maintenance as completed.
     * This will restore the item status to 'available'.
     */
    public function complete(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'cost' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($maintenance, $validated) {
            // Update maintenance status
            $maintenance->update([
                'status' => 'completed',
                'completed_date' => Carbon::now(),
                'cost' => $validated['cost'] ?? $maintenance->cost,
                'notes' => $validated['notes'] ?? $maintenance->notes,
            ]);

            // Restore item status to available (only if it's currently in maintenance)
            if ($maintenance->item->status === 'maintenance') {
                $maintenance->item->update(['status' => 'available']);
            }
        });

        return back()->with('success', 'Maintenance selesai! Status barang dikembalikan ke "Available".');
    }

    /**
     * Remove the specified maintenance from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $itemId = $maintenance->item_id;
        $maintenance->delete();

        return redirect()->route('items.show', $itemId)
            ->with('success', 'Data maintenance berhasil dihapus.');
    }
}
