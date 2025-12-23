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

class ItemController extends Controller
{
    // =========================
    // INDEX
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

    $items = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();
    $rooms = \App\Models\Room::orderBy('name')->get();

    return view('items.index', compact('items', 'rooms'));
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
            'serial_number'        => 'required|string|max:255|unique:items,serial_number',
            'room_id'              => 'required|exists:rooms,id',
            'quantity'             => 'required|integer|min:1',
            'source'               => 'nullable|string|max:255',
            'acquisition_year'     => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group'         => 'nullable|string|max:255',
            'status'               => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition'            => 'required|in:good,damaged,broken', // Validasi Kondisi Baru
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,id',
        ]);

        // 1. Initial Insert ke Database
        $item = Item::create($validated);

        // 2. Generate QR Code Handling
        $this->generateAndSaveQr($item);

        // 3. Sync Relation
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
            'serial_number'        => 'required|string|max:255|unique:items,serial_number,' . $item->id,
            'room_id'              => 'required|exists:rooms,id',
            'quantity'             => 'required|integer|min:1',
            'source'               => 'nullable|string|max:255',
            'acquisition_year'     => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group'         => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition'            => 'required|in:good,damaged,broken', // Validasi Kondisi Baru
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,id',
        ]);

       $qrFieldsChanged = ($validated['name'] !== $item->name) || 
                       ($validated['asset_number'] !== $item->asset_number) || 
                       ($validated['serial_number'] !== $item->serial_number) ||
                       ($validated['room_id'] !== $item->room_id) ||
                       ($validated['condition'] !== $item->condition);

        if ($qrFieldsChanged) {
            // Purging file QR lama dari storage disk public untuk integritas data
            if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                Storage::disk('public')->delete($item->qr_code);
            }
            
            // Melakukan persistensi data sebelum regenerasi QR
            $item->update($validated);
            
            // Eksekusi ulang prosedur regenerasi QR dengan data terbaru
            $this->generateAndSaveQr($item);
        } else {
            $item->update($validated);
        }

        $item->categories()->sync($request->categories ?? []);

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    // =========================
    // DESTROY
    // =========================
    public function destroy(Item $item)
    {
        // Opsional: Hapus file QR saat item dihapus agar tidak menumpuk sampah
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

    // =========================
    // PRIVATE METHODS
    // =========================
    private function generateAndSaveQr(Item $item)
    {
        // Tentukan Path Penyimpanan
        $qrPath = 'qr/items/' . $item->id . '.svg';

        $item->load('room');
        $roomName = $item->room ? $item->room->name : 'N/A';

        $qrPayload = "Item Name: " . $item->name . "\r\n" .
                 "Asset No: " . ($item->asset_number ?? 'N/A') . "\r\n" .
                 "Serial No: " . $item->serial_number . "\r\n" .
                 "Room Name: " . $roomName . "\r\n" .
                 "Condition: " . $item->condition;
        // Generate Konten QR (IO Operation)
        $qrContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate( $qrPayload);

        // Simpan File ke Disk
        Storage::disk('public')->put($qrPath, $qrContent);

        // Update Kolom Database
        // Menggunakan updateQuietly jika tidak ingin mentrigger updated_at, 
        // tapi update() biasa aman di sini.
        $item->update(['qr_code' => $qrPath]);
    }

    // Tambahkan di App\Http\Controllers\ItemController.php

// 1. Tampilkan Halaman Barang Keluar
public function outIndex()
{
    $items = Item::where('status', 'dikeluarkan')
        ->with(['room']) // Anda bisa join ke itemOutLogs jika ingin detail
        ->orderBy('updated_at', 'DESC')
        ->paginate(10);

    return view('items.out_index', compact('items'));
}

// 2. Form untuk mengisi data pengeluaran
public function outCreate(Item $item)
{
    return view('items.out_form', compact('item'));
}

// 3. Proses simpan data pengeluaran
public function outStore(Request $request, Item $item)
{
    $validated = $request->validate([
        'recipient_name' => 'required|string|max:255',
        'out_date'       => 'required|date',
        'reason'         => 'nullable|string',
        // Validasi File: Wajib file, tipe pdf/img, max 2MB
        'reference_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
    ]);

    // Handle File Upload
    $filePath = null;
    if ($request->hasFile('reference_file')) {
        // Simpan ke folder 'storage/app/public/surat_keluar'
        $filePath = $request->file('reference_file')->store('surat_keluar', 'public');
    }

    // Update Item Status
    $item->update(['status' => 'dikeluarkan']);

    // Simpan Log
    \App\Models\ItemOutLog::create([
        'item_id'        => $item->id,
        'recipient_name' => $validated['recipient_name'],
        'out_date'       => $validated['out_date'],
        'reason'         => $validated['reason'],
        'reference_file' => $filePath, // Simpan path-nya
    ]);

    return redirect()->route('items.out.index')
        ->with('success', 'Barang berhasil dikeluarkan dan surat tersimpan.');
}

// 4. Generate PDF Surat Pengeluaran
public function downloadOutPdf(Item $item)
{
    // Ambil data log pengeluaran terakhir
    $log = \App\Models\ItemOutLog::where('item_id', $item->id)->latest()->first();
    
    $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))
              ->setPaper('a4', 'portrait');
              
    return $pdf->stream('Surat_Keluar_'.$item->serial_number.'.pdf');
}

public function outPdf(Item $item)
{
    // Ambil data log pengeluaran terakhir dari barang ini
    $log = ItemOutLog::where('item_id', $item->id)->latest()->first();

    // Validasi jika data log tidak ditemukan (misal barang belum dikeluarkan)
    if (!$log) {
        return redirect()->back()->with('error', 'Data pengeluaran tidak ditemukan untuk item ini.');
    }

    // Generate PDF menggunakan view 'items.out-pdf'
    // 'setPaper' bisa diatur 'a4' atau 'a5' sesuai kebutuhan
    $pdf = Pdf::loadView('items.out-pdf', compact('item', 'log'))
              ->setPaper('a4', 'portrait');

    // 'stream' agar terbuka di browser dulu (preview), kalau mau langsung download ganti jadi 'download'
    return $pdf->stream('Surat_Jalan_' . str_replace(' ', '_', $item->serial_number) . '.pdf');
}
}