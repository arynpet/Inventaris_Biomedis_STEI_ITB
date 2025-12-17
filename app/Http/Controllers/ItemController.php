<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleSoftwareIO\QrCode\Generator;
use Barryvdh\DomPDF\Facade\Pdf;


use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $items = Item::with(['room', 'categories'])
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('items.index', compact('items'));
    }

    // =========================
    // CREATE FORM
    // =========================
    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('rooms', 'categories'));
    }

    // =========================
    // STORE
    // =========================
public function store(Request $request)
{
    $validated = $request->validate([
        'name'                 => 'required|string|max:255',
        'asset_number'         => 'nullable|string|max:255',
        'serial_number'        => 'required|string|max:255',
        'room_id'              => 'required|exists:rooms,id',
        'quantity'             => 'required|integer|min:1',
        'source'               => 'nullable|string|max:255',
        'acquisition_year'     => 'nullable|digits:4|integer',
        'placed_in_service_at' => 'nullable|date',
        'fiscal_group'         => 'nullable|string|max:255',
        'status'               => 'required|in:available,borrowed,maintenance',
        'categories'           => 'nullable|array',
        'categories.*'         => 'exists:categories,id',
    ]);

    // 1. Simpan item
    $item = Item::create($validated);

    // 2. Generate QR
    $qrFileName = 'qr/items/'.$item->id.'.svg';
    

    Storage::disk('public')->put(
        $qrFileName,
        QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')

            ->generate($item->serial_number)
    );

    // 3. Simpan path QR
    $item->update([
        'qr_code' => $qrFileName
    ]);

    // 4. Sync kategori
    if ($request->categories) {
        $item->categories()->sync($request->categories);
    }

    return redirect()->route('items.index')
        ->with('success', 'Item berhasil dibuat & QR otomatis dibuat.');
}



    // =========================
    // EDIT FORM
    // =========================
    public function edit(Item $item)
    {
        $rooms = Room::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'rooms', 'categories'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'asset_number'         => 'nullable|string|max:255',
            'serial_number'        => 'nullable|string|max:255',
            'room_id'              => 'required|exists:rooms,id',
            'quantity'             => 'required|integer|min:1',
            'source'               => 'nullable|string|max:255',
            'acquisition_year'     => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group'         => 'nullable|string|max:255',
            'status'               => 'required|in:available,borrowed,maintenance',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,id',
        ]);

        /**
         * Update qr_code kalau serial_number berubah
         */
        if (!empty($validated['serial_number'])) {
            $validated['qr_code'] = $validated['serial_number'];
        } else {
            $validated['qr_code'] = null;
        }

        // Update item
        $item->update($validated);

        // Sync categories
        $item->categories()->sync($request->categories ?? []);

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    // =========================
    // DESTROY
    // =========================
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function qrPdf(Item $item)
{
    return Pdf::loadView('items.qr-pdf', compact('item'))
        ->setPaper('a4')
        ->stream('qr-'.$item->serial_number.'.pdf');
}

    public function show(Item $item)
{
    return view('items.show', compact('item'));
}



}
