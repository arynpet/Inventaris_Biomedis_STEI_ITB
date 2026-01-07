<?php

namespace App\Http\Controllers;

use App\Models\MaterialType;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller
{
    /**
     * Tampilkan daftar material dengan Filter & Search
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter
        $search = $request->input('search');
        $category = $request->input('category');

        // 2. Query Builder
        $materials = MaterialType::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10) // Pagination
            ->withQueryString();

        return view('materials.index', compact('materials'));
    }

    /**
     * Bulk Action (Hapus Banyak)
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action_type');
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) return back()->with('error', 'Tidak ada item dipilih.');

        if ($action === 'delete') {
            MaterialType::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' material berhasil dihapus.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'category'      => 'required|in:filament,resin',
                'name'          => 'required|string|max:255',
                'stock_balance' => 'required|numeric|min:0',
                'unit'          => 'required|in:gram,mililiter',
            ]);

            MaterialType::create($request->only(['category', 'name', 'stock_balance', 'unit']));

            return redirect()->route('materials.index')
                ->with('success', 'Material type berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah material: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $material = MaterialType::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'category' => 'required|in:filament,resin',
                'name'     => 'required|string|max:255',
            ]);

            $material = MaterialType::findOrFail($id);
            $material->update($request->only('category', 'name'));

            return redirect()->route('materials.index')
                ->with('success', 'Material type berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update material: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $material = MaterialType::findOrFail($id);
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material type berhasil dihapus!');
    }

    /**
     * Fitur Tambah Stok Cepat
     */
    public function addStock(Request $request, $id)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);

            $material = MaterialType::findOrFail($id);
            
            // Menambah stok
            $material->increment('stock_balance', $request->amount);

            return back()->with('success', "Berhasil menambah stok {$request->amount} {$material->unit} ke {$material->name}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }
}