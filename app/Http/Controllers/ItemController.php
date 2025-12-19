<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'serial_number'        => 'required|string|max:255|unique:items,serial_number',
            'room_id'              => 'required|exists:rooms,id',
            'quantity'             => 'required|integer|min:1',
            'source'               => 'nullable|string|max:255',
            'acquisition_year'     => 'nullable|digits:4|integer',
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group'         => 'nullable|string|max:255',
            'status'               => 'required|in:available,borrowed,maintenance',
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
            'status'               => 'required|in:available,borrowed,maintenance',
            'condition'            => 'required|in:good,damaged,broken', // Validasi Kondisi Baru
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,id',
        ]);

        // Cek jika Serial Number berubah, maka QR Code harus diganti
        if ($validated['serial_number'] !== $item->serial_number) {
            // Hapus QR Lama jika ada
            if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
                Storage::disk('public')->delete($item->qr_code);
            }
            
            // Update data item dulu (termasuk serial number baru)
            $item->update($validated);
            
            // Generate ulang QR Code dengan serial number baru
            $this->generateAndSaveQr($item);
        } else {
            // Jika serial number tidak berubah, update data seperti biasa (termasuk condition)
            $item->update($validated);
        }

        // Sync Kategori
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

        // Generate Konten QR (IO Operation)
        $qrContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($item->serial_number);

        // Simpan File ke Disk
        Storage::disk('public')->put($qrPath, $qrContent);

        // Update Kolom Database
        // Menggunakan updateQuietly jika tidak ingin mentrigger updated_at, 
        // tapi update() biasa aman di sini.
        $item->update(['qr_code' => $qrPath]);
    }
}