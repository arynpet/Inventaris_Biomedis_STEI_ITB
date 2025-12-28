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
use Illuminate\Support\Str; // Dari Local (Penting untuk serial number)
use Illuminate\Support\Arr; // Dari Remote (Penting untuk array manipulation)

class ItemController extends Controller
{
    // =========================
    // INDEX dengan Grouping
    // =========================
    public function index(Request $request)
    {
        $query = Item::with(['room', 'categories']);

        // Filter Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status & Room
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Opsi Grouping by Asset Number
        if ($request->get('group_by_asset') === '1') {
            $allItems = $query->orderBy('asset_number')->orderBy('id')->get();
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

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('rooms', 'categories'));
    }

    // =========================
    // STORE (GABUNGAN BATCH & Arr::except)
    // =========================
    public function store(Request $request)
    {
        $isBatch = $request->input('input_mode') === 'batch';

        // 1. Validasi
        $rules = [
            'name'                 => 'required|string|max:255',
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
            'asset_number'         => 'nullable|string|max:255', 
        ];

        if ($isBatch) {
            $rules['serial_numbers_batch'] = 'required|string';
        } else {
            $rules['serial_number'] = 'required|string|max:255|unique:items,serial_number';
        }

        $validated = $request->validate($rules);
        $savedCount = 0;

        if ($isBatch) {
            // --- MODE BATCH ---
            $rawSerials = preg_split('/\r\n|\r|\n/', $request->serial_numbers_batch);
            $serials = array_values(array_filter(array_map('trim', $rawSerials)));
            
            // Cek duplikat serial
            $existingSerials = Item::whereIn('serial_number', $serials)->pluck('serial_number')->toArray();
            if (!empty($existingSerials)) {
                return back()->withInput()->withErrors([
                    'serial_numbers_batch' => 'Serial Number berikut sudah ada: ' . implode(', ', $existingSerials)
                ]);
            }

            foreach ($serials as $index => $sn) {
                // Gunakan Arr::except (Fitur Remote) agar lebih bersih
                $itemData = Arr::except($validated, ['serial_numbers_batch', 'categories']);
                
                $itemData['serial_number'] = $sn;
                $itemData['name'] = $validated['name'] . ' ' . ($index + 1); // Logic Nama Berurut (Fitur Local)
                $itemData['asset_number'] = $validated['asset_number']; // Asset Number Sama

                $item = Item::create($itemData);
                $this->generateAndSaveQr($item);
                
                if ($request->categories) {
                    $item->categories()->sync($request->categories);
                }
                
                $savedCount++;
            }

            $message = "Berhasil menambahkan $savedCount item secara batch!";

        } else {
            // --- MODE SINGLE ---
            // Gunakan Arr::except (Fitur Remote)
            $itemData = Arr::except($validated, ['categories']);
            
            $item = Item::create($itemData);
            $this->generateAndSaveQr($item);

            if ($request->categories) {
                $item->categories()->sync($request->categories);
            }
            $message = 'Item berhasil dibuat.';
        }

        return redirect()->route('items.index')->with('success', $message);
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Item $item)
    {
        $rooms = Room::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('items.edit', compact('item', 'rooms', 'categories'));
    }

    // =========================
    // UPDATE (GABUNGAN QR LOGIC & Arr::except)
    // =========================
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
        ]);

        $qrFieldsChanged = ($validated['name'] !== $item->name) || 
                           ($validated['asset_number'] !== $item->asset_number) || 
                           ($validated['serial_number'] !== $item->serial_number) ||
                           ($validated['room_id'] !== $item->room_id) ||
                           ($validated['condition'] !== $item->condition);

        // Gunakan Arr::except (Fitur Remote)
        $itemData = Arr::except($validated, ['categories']);

        $message = 'Item updated successfully.';
        $alertType = 'success';
        
        if ($qrFieldsChanged) {
            if (in_array($item->status, ['available', 'maintenance'])) {
                if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                    Storage::disk('public')->delete($item->qr_code);
                }
                $item->update($itemData);
                $this->generateAndSaveQr($item);
            }
            else {
            $item->update($itemData);
            $message = 'Data berhasil diupdate, tetapi QR Code tidak diperbarui karena barang sedang dipinjam.';
            $alertType = 'warning';
            }
        } else {
            $item->update($itemData);
        }

        $item->categories()->sync($request->categories ?? []);

        return redirect()->route('items.index')->with($alertType, $message);
    }

    // =========================
    // DESTROY (SINGLE)
    // =========================
    public function destroy(Item $item)
    {
        if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
            Storage::disk('public')->delete($item->qr_code);
        }
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    // =========================
    // SHOW & PDF
    // =========================
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

    // =========================
    // BARANG KELUAR
    // =========================
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
            ->with('success', 'Barang berhasil dikeluarkan.');
    }

    public function downloadOutPdf(Item $item)
    {
        $log = ItemOutLog::where('item_id', $item->id)->latest()->first();
        $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))->setPaper('a4', 'portrait');
        return $pdf->stream('Surat_Keluar_'.$item->serial_number.'.pdf');
    }

    public function outPdf(Item $item)
    {
        $log = ItemOutLog::where('item_id', $item->id)->latest()->first();
        if (!$log) {
            return redirect()->back()->with('error', 'Data pengeluaran tidak ditemukan.');
        }
        $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))->setPaper('a4', 'portrait');
        return $pdf->stream('Surat_Jalan_' . str_replace(' ', '_', $item->serial_number) . '.pdf');
    }

    // =========================
    // BULK ACTION (Fitur Local)
    // =========================
    public function bulkAction(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:items,id',
            'action_type' => 'required|in:delete,copy',
        ]);

        $ids = $request->selected_ids;
        $action = $request->action_type;
        $count = 0;

        if ($action === 'delete') {
            $items = Item::whereIn('id', $ids)->get();
            foreach ($items as $item) {
                if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                    Storage::disk('public')->delete($item->qr_code);
                }
                $item->delete();
                $count++;
            }
            return redirect()->route('items.index')->with('success', "$count item berhasil dihapus.");
        }

        if ($action === 'copy') {
            $items = Item::whereIn('id', $ids)->orderBy('id')->get();
            foreach ($items as $item) {
                $newItem = $item->replicate();
                $newItem->name = $this->generateIncrementedName($item->name);
                $newItem->serial_number = $item->serial_number . '-CPY-' . Str::upper(Str::random(3));
                $newItem->qr_code = null;
                $newItem->push();
                
                $this->generateAndSaveQr($newItem);
                
                $categoryIds = $item->categories->pluck('id')->toArray();
                if (!empty($categoryIds)) {
                    $newItem->categories()->sync($categoryIds);
                }
                $count++;
            }
            return redirect()->route('items.index')->with('success', "$count item berhasil diduplikasi.");
        }
        return redirect()->back();
    }

    // =========================
    // REGENERATE QR (Fitur Local)
    // =========================
    public function regenerateAllQr()
    {
        $count = 0;
        Item::chunk(100, function ($items) use (&$count) {
            foreach ($items as $item) {
                $this->generateAndSaveQr($item);
                $count++;
            }
        });
        return redirect()->route('items.index')->with('success', "Berhasil refresh $count QR Code.");
    }

    // =========================
    // HELPERS
    // =========================
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

        $qrContent = QrCode::format('svg')->size(300)->margin(2)->errorCorrection('H')->generate($qrPayload);
        Storage::disk('public')->put($qrPath, $qrContent);
        $item->update(['qr_code' => $qrPath]);
    }

    private function generateIncrementedName($originalName)
    {
        if (preg_match('/^(.*?) (\d+)$/', $originalName, $matches)) {
            $baseName = $matches[1];
            $number   = (int)$matches[2];
        } else {
            $baseName = $originalName;
            $number   = 0;
        }

        do {
            $number++;
            $newName = $baseName . ' ' . $number;
        } while (Item::where('name', $newName)->exists());

        return $newName;
    }
}