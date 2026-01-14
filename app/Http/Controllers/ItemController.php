<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use App\Models\ItemOutLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            $query->where(function ($q) use ($request) {
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
            $groupedItems = $allItems->groupBy(function ($item) {
                return $item->asset_number ?? 'no-asset-' . $item->id;
            });
            $rooms = Room::orderBy('name')->get();
            return view('items.index_grouped', compact('groupedItems', 'rooms'));
        }

        // Default: tampilan list biasa
        if ($request->get('show_all') == '1') {
            $items = $query->orderBy('id', 'DESC')->get();
        } else {
            $items = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        }
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
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'source' => 'nullable|string|max:255',
            'acquisition_year' => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group' => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition' => 'required|in:good,damaged,broken',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'asset_number' => 'nullable|string|max:255',
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
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'asset_number' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255|unique:items,serial_number,' . $item->id,
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'source' => 'nullable|string|max:255',
            'acquisition_year' => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group' => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition' => 'required|in:good,damaged,broken',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
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
            } else {
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
        $item->delete(); // Ini sekarang menjadi Soft Delete (database only)

        // Kirim session 'action_undo' ke View untuk memunculkan tombol
        return redirect()->route('items.index')
            ->with('success', 'Item berhasil dihapus.')
            ->with('action_undo', route('items.restore', $item->id));
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
            ->stream('qr-' . $item->serial_number . '.pdf');
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
            'out_date' => 'required|date',
            'reason' => 'nullable|string',
            'reference_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('reference_file')) {
            $filePath = $request->file('reference_file')->store('surat_keluar', 'public');
        }

        $item->update(['status' => 'dikeluarkan']);

        ItemOutLog::create([
            'item_id' => $item->id,
            'recipient_name' => $validated['recipient_name'],
            'out_date' => $validated['out_date'],
            'reason' => $validated['reason'],
            'reference_file' => $filePath,
        ]);

        return redirect()->route('items.out.index')
            ->with('success', 'Barang berhasil dikeluarkan.');
    }

    public function downloadOutPdf(Item $item)
    {
        $log = ItemOutLog::where('item_id', $item->id)->latest()->first();
        $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))->setPaper('a4', 'portrait');
        return $pdf->stream('Surat_Keluar_' . $item->serial_number . '.pdf');
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
// =========================
    // BULK ACTION (DENGAN UNDO)
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

        // ✅ H3 FIX: Wrap in DB transaction for atomicity
        try {
            DB::transaction(function () use ($ids, $action, &$count) {
                // --- AKSI DELETE ---
                if ($action === 'delete') {
                    $items = Item::whereIn('id', $ids)->get();
                    $deletedIds = []; // Array untuk menampung ID yang dihapus

                    foreach ($items as $item) {
                        // HAPUS bagian Storage::delete agar file QR tidak hilang fisik
                        // if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) { ... }

                        $item->delete(); // Ini melakukan Soft Delete
                        $deletedIds[] = $item->id; // Simpan ID untuk keperluan undo
                        $count++;
                    }

                    // Buat URL Undo dengan mengirim ID yang dipisahkan koma (misal: 1,2,5)
                    // Kita implode array jadi string agar mudah dikirim lewat URL
                    $idsString = implode(',', $deletedIds);
                    $undoUrl = route('items.bulk_restore', ['ids' => $idsString]);

                    session()->flash('action_undo', $undoUrl);

                    // ✅ LOGGING untuk bulk delete
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'bulk_delete',
                        'model' => 'Item',
                        'model_id' => null,
                        'description' => "Bulk delete: {$count} items (IDs: {$idsString})",
                        'ip_address' => request()->ip(),
                    ]);
                }

                // --- AKSI COPY ---
                if ($action === 'copy') {
                    $items = Item::whereIn('id', $ids)->orderBy('id')->get();
                    foreach ($items as $item) {
                        $newItem = $item->replicate();
                        $newItem->name = $this->generateIncrementedName($item->name);
                        // Serial number unik
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

                    // ✅ LOGGING untuk bulk copy
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'bulk_copy',
                        'model' => 'Item',
                        'model_id' => null,
                        'description' => "Bulk copy: {$count} items duplicated",
                        'ip_address' => request()->ip(),
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }

        if ($action === 'delete') {
            return redirect()->route('items.index')
                ->with('success', "$count item berhasil dihapus.");
        }

        if ($action === 'copy') {
            return redirect()->route('items.index')->with('success', "$count item berhasil diduplikasi.");
        }

        return redirect()->back();
    }

    // =========================
    // REGENERATE QR (Fitur Local)
    // =========================
    public function regenerateAllQr(Request $request)
    {
        // TODO: For production with thousands of items, move to Laravel Queue
        // Example: RegenerateQrJob::dispatch();

        // Batch size limit to prevent timeout (max 100 items per request)
        $limit = $request->input('limit', 100);
        $offset = $request->input('offset', 0);

        $count = 0;
        $totalItems = Item::count();
        $remaining = max(0, $totalItems - $offset);

        // Process limited batch
        Item::skip($offset)
            ->take(min($limit, $remaining))
            ->chunk(50, function ($items) use (&$count) {
                foreach ($items as $item) {
                    $this->generateAndSaveQr($item);
                    $count++;
                }
            });

        $newOffset = $offset + $count;
        $stillRemaining = $totalItems - $newOffset;

        // If there are still items remaining, show continue button
        if ($stillRemaining > 0) {
            $continueUrl = route('items.regenerate_qr') . '?offset=' . $newOffset . '&limit=' . $limit;
            return redirect()->route('items.index')
                ->with('warning', "Diproses $count QR code. Masih tersisa $stillRemaining item.")
                ->with('continue_url', $continueUrl)
                ->with('continue_text', 'Lanjutkan Regenerasi QR');
        }

        return redirect()->route('items.index')
            ->with('success', "Berhasil regenerasi total $newOffset QR code!");
    }

    // =========================
    // HELPERS
    // =========================
    private function generateAndSaveQr(Item $item)
    {
        // Generate unique path dengan microtime + random string untuk menjamin uniqueness
        $timestamp = (int) (microtime(true) * 10000); // Mikrodetik untuk uniqueness
        $randomSuffix = Str::random(6); // Tambahkan random string untuk mencegah collision
        $qrPath = 'qr/items/' . $item->id . '-' . $timestamp . '-' . $randomSuffix . '.svg';

        // Hapus QR lama jika ada
        if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
            Storage::disk('public')->delete($item->qr_code);
        }

        $item->load('room');
        $roomName = $item->room ? $item->room->name : 'N/A';

        $qrPayload = $item->name . "\n" . $item->serial_number;

        // Generate QR Code
        // Size tetap 300, tapi karena payload lebih pendek, "kotak-kotak" akan otomatis lebih besar/renggang
        $qrContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('M') // Turunkan level koreksi ke 'M' (Medium) atau 'L' (Low) agar lebih renggang
            ->generate($qrPayload);

        Storage::disk('public')->put($qrPath, $qrContent);
        $item->update(['qr_code' => $qrPath]);
    }

    private function generateIncrementedName($originalName)
    {
        if (preg_match('/^(.*?) (\d+)$/', $originalName, $matches)) {
            $baseName = $matches[1];
            $number = (int) $matches[2];
        } else {
            $baseName = $originalName;
            $number = 0;
        }

        do {
            $number++;
            $newName = $baseName . ' ' . $number;
        } while (Item::where('name', $newName)->exists());

        return $newName;
    }

    // =========================
    // RESTORE (FITUR URUNGKAN)
    // =========================
    public function restore($id)
    {
        // Cari item yang sudah dihapus (withTrashed)
        $item = Item::withTrashed()->find($id);

        if ($item && $item->trashed()) {
            $item->restore(); // Kembalikan data
            return redirect()->route('items.index')->with('success', 'Penghapusan berhasil dibatalkan (Data dikembalikan).');
        }

        return redirect()->route('items.index')->with('error', 'Data tidak ditemukan atau tidak dalam status terhapus.');
    }

    // =========================
    // BULK RESTORE (LOGIC TOMBOL UNDO MASAL)
    // =========================
    public function bulkRestore(Request $request)
    {
        // Ambil list ID dari URL (contoh: ?ids=1,2,3)
        $idsString = $request->query('ids');

        if ($idsString) {
            $ids = explode(',', $idsString);

            // Restore semua item yang ID-nya ada di list tersebut
            // whereIn + withTrashed (karena statusnya sudah terhapus)
            Item::withTrashed()->whereIn('id', $ids)->restore();

            return redirect()->route('items.index')
                ->with('success', 'Penghapusan masal berhasil dibatalkan.');
        }

        return redirect()->route('items.index')->with('error', 'Gagal mengembalikan data.');
    }

    // =========================
    // BULK TERMINATE (HAPUS PERMANEN MASAL)
    // =========================
    public function bulkTerminate(Request $request)
    {
        // Validasi input ids string "1,2,3"
        $idsString = $request->input('ids');

        if (!$idsString) {
            return redirect()->route('items.trash')->with('error', 'Tidak ada item yang dipilih.');
        }

        $ids = explode(',', $idsString);
        $count = 0;

        // Ambil item yang ada di trash
        $items = Item::onlyTrashed()->whereIn('id', $ids)->get();

        foreach ($items as $item) {
            // Cek policy jika perlu: $this->authorize('terminate', $item);

            // Simpan log sebelum hapus
            $itemName = $item->name;
            $itemSN = $item->serial_number ?? 'N/A';

            $item->forceDelete();

            // Logging per item atau bulk log nanti
            $count++;
        }

        if ($count > 0) {
            // Bulk Log
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_terminate',
                'model' => 'Item',
                'model_id' => null,
                'description' => "Bulk permanent delete: {$count} items (IDs: {$idsString})",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('items.trash')
                ->with('success', "$count item berhasil dihapus permanen.");
        }

        return redirect()->route('items.trash')->with('error', 'Gagal menghapus data.');
    }

    public function trash(Request $request)
    {
        // 1. Ambil Data untuk Dropdown Filter
        $categories = Category::all();
        $rooms = Room::all();

        // 2. Ambil Input Filter, Search, Sort
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $room_id = $request->input('room_id');
        $sort = $request->input('sort', 'deleted_at');
        $direction = $request->input('direction', 'desc');

        // 3. Query Data Sampah dengan Filter
        $deletedItems = Item::onlyTrashed()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhere('asset_number', 'like', "%{$search}%");
                });
            })
            ->when($category_id, function ($query, $catId) {
                return $query->where('category_id', $catId);
            })
            ->when($room_id, function ($query, $roomId) {
                return $query->where('room_id', $roomId);
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString(); // Penting: agar filter tidak hilang saat ganti halaman

        return view('items.trash', compact('deletedItems', 'categories', 'rooms'));
    }

    public function terminate($id)
    {
        $item = Item::onlyTrashed()->where('id', $id)->first();

        if (!$item) {
            return redirect()->route('items.trash')->with('error', 'Data tidak ditemukan.');
        }

        $this->authorize('terminate', $item);

        // Simpan data untuk logging SEBELUM dihapus permanent
        $itemName = $item->name;
        $itemSN = $item->serial_number ?? 'N/A';
        $itemAsset = $item->asset_number ?? 'N/A';

        $item->forceDelete();

        // ✅ LOGGING untuk hard delete permanent (CRITICAL ACTION)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'terminate',
            'model' => 'Item',
            'model_id' => $id,
            'description' => "Hard delete permanent: '{$itemName}' (SN: {$itemSN}, Asset: {$itemAsset})",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('items.trash')
            ->with('success', 'Data berhasil di-terminate (dihapus selamanya).');
    }

    // ===================================
    // API HELPERS FOR FRONTEND
    // ===================================
    public function getNextSequence(Request $request)
    {
        // Validasi input
        $prefix = $request->input('prefix'); // e.g., 'E'
        $abbr = $request->input('abbr');     // e.g., 'TRML'
        $year = $request->input('year');     // e.g., '26'

        if (!$prefix || !$abbr || !$year) {
            return response()->json(['sequence' => '001', 'error' => 'Missing params']);
        }

        // Kita cari serial number yang mirip: "E-TRML-26%"
        // Asumsi format bakunya adalah PREFIX-ABBR-YEAR[SEQ]
        $pattern = "{$prefix}-{$abbr}-{$year}%";

        // Cari item terakhir yang sesuai pattern
        // Menggunakan natural sort order untuk bagian sequence bisa tricky di SQL murni, jadi kita ambil yang terbaru by created_at sebagai estimasi cepat
        // ATAU kita ambil max serial_number stringnya.

        $latestItem = Item::where('serial_number', 'like', $pattern)
            ->orderBy('serial_number', 'desc') // String sorting might be enough if format is strict
            ->first();

        if ($latestItem) {
            // Extract sequence number (3 digit terakhir)
            // Contoh: E-TRML-26005 -> ambil 005
            // Kita coba ambil numeric part terakhir
            if (preg_match('/(\d+)$/', $latestItem->serial_number, $matches)) {
                // matches[1] = "26005" kemungkinan.
                // Jika formatnya '26' + '005', maka kita ambil 3 digit terakhir
                $lastSeqStr = substr($matches[1], 2); // Buang 2 digit tahun (26)
                $lastSeq = (int) $lastSeqStr;
                $nextSeq = $lastSeq + 1;
            } else {
                $nextSeq = 1;
            }
        } else {
            $nextSeq = 1;
        }

        // Format ulang jadi 001, 002 dst
        $formattedSeq = str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'sequence' => $formattedSeq,
            'full_preview' => "{$prefix}-{$abbr}-{$year}{$formattedSeq}"
        ]);
    }
}