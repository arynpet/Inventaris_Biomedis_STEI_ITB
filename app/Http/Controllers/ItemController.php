<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use App\Models\ItemOutLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;

class ItemController extends Controller
{
    // =========================
    // INDEX dengan Grouping
    // =========================
    public function index(Request $request)
    {
        $query = Item::with(['room', 'categories']);

        // Filter Search (Nama, Serial, Asset Number)
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Ruangan
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Opsi Grouping by Asset Number
        if ($request->get('group_by_asset') === '1') {
            // Ambil semua items yang match filter
            $allItems = $query->orderBy('asset_number')->orderBy('id')->get();
            
            // Group by asset_number
            $groupedItems = $allItems->groupBy(function($item) {
                return $item->asset_number ?? 'no-asset-' . $item->id;
            });
            
            $rooms = Room::orderBy('name')->get();
            
            return view('items.index_grouped', compact('groupedItems', 'rooms'));
        }

        // Default: tampilan list biasa
        $items = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        $rooms = Room::orderBy('name')->get();

        return view('items.index', compact('items', 'rooms'));
    }

    // Sisanya tetap sama seperti sebelumnya...
    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('rooms', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'asset_number'         => 'nullable|string|max:255',
            'serial_number'        => 'required|string|max:255|unique:items,serial_number',
            'room_id'              => 'required|exists:rooms,id',
            'quantity'             => 'required|integer|min:1',
            'source'               => 'nullable|string|max:255',
            'acquisition_year'     => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group'         => 'nullable|string|max:255',
            'status'               => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition'            => 'required|in:good,damaged,broken',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,id',
        ], [
            'asset_number.unique' => 'Nomor Asset sudah digunakan untuk barang lain.',
        ]);

        // FIX: Hapus 'categories' dari array sebelum create
        $itemData = Arr::except($validated, ['categories']);
        
        $item = Item::create($itemData);
        $this->generateAndSaveQr($item);

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

public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'asset_number'         => 'nullable|string|max:255',
            'serial_number'        => 'required|string|max:255|unique:items,serial_number,' . $item->id,
            'room_id'              => 'required|exists:rooms,id',
            'quantity'             => 'required|integer|min:1',
            'source'               => 'nullable|string|max:255',
            'acquisition_year'     => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group'         => 'nullable|string|max:255',
            'status'               => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition'            => 'required|in:good,damaged,broken',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,id',
        ], [
            'asset_number.unique' => 'Nomor Asset sudah digunakan untuk barang lain.',
        ]);

        $qrFieldsChanged = ($validated['name'] !== $item->name) || 
                       ($validated['asset_number'] !== $item->asset_number) || 
                       ($validated['serial_number'] !== $item->serial_number) ||
                       ($validated['room_id'] !== $item->room_id) ||
                       ($validated['condition'] !== $item->condition);

        // FIX: Hapus 'categories' dari array sebelum update
        $itemData = Arr::except($validated, ['categories']);

        if ($qrFieldsChanged) {
            if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                Storage::disk('public')->delete($item->qr_code);
            }
            
            $item->update($itemData);
            $this->generateAndSaveQr($item);
        } else {
            $item->update($itemData);
        }

        $item->categories()->sync($request->categories ?? []);

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
            Storage::disk('public')->delete($item->qr_code);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function qrPdf(Item $item)
    {
        return Pdf::loadView('items.qr-pdf', compact('item'))
            ->setPaper('a4')
            ->stream('qr-'.$item->serial_number.'.pdf');
    }

    private function generateAndSaveQr(Item $item)
    {
        $qrPath = 'qr/items/' . $item->id . '.svg';

        $item->load('room');
        $roomName = $item->room ? $item->room->name : 'N/A';

        $qrPayload = "Item Name: " . $item->name . "\r\n" .
                 "Asset No: " . ($item->asset_number ?? 'N/A') . "\r\n" .
                 "Serial No: " . $item->serial_number . "\r\n" .
                 "Room Name: " . $roomName . "\r\n" .
                 "Condition: " . $item->condition;

        $qrContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($qrPayload);

        Storage::disk('public')->put($qrPath, $qrContent);
        $item->update(['qr_code' => $qrPath]);
    }

    // Tambahkan di App\Http\Controllers\ItemController.php

public function outIndex()
    {
        $items = Item::where('status', 'dikeluarkan')
            ->with(['room'])
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);

        return view('items.out_index', compact('items'));
    }

    public function outCreate(Item $item)
    {
        return view('items.out_form', compact('item'));
    }

    public function outStore(Request $request, Item $item)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'out_date'       => 'required|date',
            'reason'         => 'nullable|string',
            'reference_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('reference_file')) {
            $filePath = $request->file('reference_file')->store('surat_keluar', 'public');
        }

        $item->update(['status' => 'dikeluarkan']);

        ItemOutLog::create([
            'item_id'        => $item->id,
            'recipient_name' => $validated['recipient_name'],
            'out_date'       => $validated['out_date'],
            'reason'         => $validated['reason'],
            'reference_file' => $filePath,
        ]);

        return redirect()->route('items.out.index')
            ->with('success', 'Barang berhasil dikeluarkan dan surat tersimpan.');
    }

    public function downloadOutPdf(Item $item)
    {
        $log = ItemOutLog::where('item_id', $item->id)->latest()->first();
        
        $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))
              ->setPaper('a4', 'portrait');
              
        return $pdf->stream('Surat_Keluar_'.$item->serial_number.'.pdf');
    }

    public function outPdf(Item $item)
    {
        $log = ItemOutLog::where('item_id', $item->id)->latest()->first();

        if (!$log) {
            return redirect()->back()->with('error', 'Data pengeluaran tidak ditemukan untuk item ini.');
        }

        $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))
              ->setPaper('a4', 'portrait');

        return $pdf->stream('Surat_Jalan_' . str_replace(' ', '_', $item->serial_number) . '.pdf');
    }
}