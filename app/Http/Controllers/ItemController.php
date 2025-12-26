<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ItemOutLog; // Tambahkan ini juga
use Illuminate\Support\Str;

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

// =========================
    // STORE (SINGLE & BATCH)
    // =========================
// =========================
    // STORE (REVISI: Asset Number Sama, Nama Berurut)
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

        // 2. Proses Penyimpanan
        $savedCount = 0;

        if ($isBatch) {
            // --- MODE BATCH ---
            
            // Pecah serial number per baris
            $rawSerials = preg_split('/\r\n|\r|\n/', $request->serial_numbers_batch);
            $serials = array_values(array_filter(array_map('trim', $rawSerials)));
            
            // Cek duplikat serial di DB
            $existingSerials = Item::whereIn('serial_number', $serials)->pluck('serial_number')->toArray();
            if (!empty($existingSerials)) {
                return back()->withInput()->withErrors([
                    'serial_numbers_batch' => 'Serial Number berikut sudah ada: ' . implode(', ', $existingSerials)
                ]);
            }

            // Loop Simpan
            foreach ($serials as $index => $sn) {
                // Siapkan data dasar (kecuali serial batch & categories)
                $itemData = collect($validated)->except(['serial_numbers_batch', 'categories'])->toArray();
                
                // A. Set Serial Number (Beda-beda setiap item)
                $itemData['serial_number'] = $sn;

                // B. Logic Nama Berurut (PC Lab 1, PC Lab 2, dst)
                // Kalau mau nama SAMA PERSIS juga, hapus bagian . ' ' . ($index + 1) ini
                $itemData['name'] = $validated['name'] . ' ' . ($index + 1);

                // C. Asset Number (SAMA SEMUA dalam satu batch)
                // Kita ambil langsung dari inputan tanpa diubah-ubah
                $itemData['asset_number'] = $validated['asset_number'];

                // Create Item
                $item = Item::create($itemData);
                $this->generateAndSaveQr($item);
                
                if ($request->categories) {
                    $item->categories()->sync($request->categories);
                }
                
                $savedCount++;
            }

            $message = "Berhasil menambahkan $savedCount item secara batch!";

        } else {
            // --- MODE SINGLE (Tetap sama) ---
            $item = Item::create($validated);
            $this->generateAndSaveQr($item);

            if ($request->categories) {
                $item->categories()->sync($request->categories);
            }
            $message = 'Item berhasil dibuat.';
        }

        return redirect()->route('items.index')->with('success', $message);
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

        if ($qrFieldsChanged) {
            if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                Storage::disk('public')->delete($item->qr_code);
            }
            
            $item->update($validated);
            $this->generateAndSaveQr($item);
        } else {
            $item->update($validated);
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

    // =========================
    // NEW FEATURE: BULK ACTION (DELETE / COPY)
    // =========================
// =========================
    // BULK ACTION (UPDATED LOGIC)
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

        // --- ACTION DELETE ---
        if ($action === 'delete') {
            $items = Item::whereIn('id', $ids)->get();
            foreach ($items as $item) {
                if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                    Storage::disk('public')->delete($item->qr_code);
                }
                $item->delete();
                $count++;
            }
            return redirect()->route('items.index')
                ->with('success', "$count item berhasil dihapus.");
        }

        // --- ACTION COPY (REVISI BERURUT) ---
        if ($action === 'copy') {
            // Kita sorting berdasarkan ID agar urutan copy-nya rapi
            $items = Item::whereIn('id', $ids)->orderBy('id')->get();

            foreach ($items as $item) {
                $newItem = $item->replicate();
                
                // 1. Generate Nama Berurut (Meja -> Meja 1 -> Meja 2)
                $newItem->name = $this->generateIncrementedName($item->name);
                
                // 2. Serial Number Unik (Tetap butuh random agar tidak error database unique)
                $newItem->serial_number = $item->serial_number . '-COPY-' . Str::upper(Str::random(4));
                
                // 3. Reset QR & Save
                $newItem->qr_code = null;
                $newItem->push(); 
                
                // 4. Generate QR Baru & Copy Kategori
                $this->generateAndSaveQr($newItem);
                
                $categoryIds = $item->categories->pluck('id')->toArray();
                if (!empty($categoryIds)) {
                    $newItem->categories()->sync($categoryIds);
                }

                $count++;
            }

            return redirect()->route('items.index')
                ->with('success', "$count item berhasil diduplikasi.");
        }

        return redirect()->back()->with('error', 'Aksi tidak valid.');
    }

    // =========================
    // HELPER BARU: GENERATE NAMA BERURUT
    // =========================
    private function generateIncrementedName($originalName)
    {
        // Cek apakah nama aslinya sudah berakhiran angka (Misal: "Meja 1")
        // Regex: Ambil teks apapun di depan, spasi, lalu angka di akhir
        if (preg_match('/^(.*?) (\d+)$/', $originalName, $matches)) {
            $baseName = $matches[1]; // "Meja"
            $number   = (int)$matches[2]; // 1
        } else {
            // Jika tidak ada angka (Misal: "Meja")
            $baseName = $originalName;
            $number   = 0;
        }

        // Loop cari nama yang tersedia di database
        do {
            $number++; // Naikkan angka (0 jadi 1, 1 jadi 2, dst)
            $newName = $baseName . ' ' . $number; // Gabungkan: "Meja 1"
        } while (Item::where('name', $newName)->exists()); // Cek DB, kalau ada, ulang loop naikkan angka lagi

        return $newName;
    }

    // =========================
    // FITUR: REGENERATE ALL QR
    // =========================
    public function regenerateAllQr()
    {
        // Ambil semua item yang ada di database
        // Gunakan chunk() untuk hemat memori jika datanya ribuan
        $count = 0;
        
        Item::chunk(100, function ($items) use (&$count) {
            foreach ($items as $item) {
                // Panggil fungsi private yang sudah ada
                $this->generateAndSaveQr($item);
                $count++;
            }
        });

        return redirect()->route('items.index')
            ->with('success', "Berhasil membuat ulang $count QR Code.");
    }
}