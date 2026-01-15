<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    /**
     * Display the public catalog.
     */
    public function index(Request $request)
    {
        // 1. Ambil Parameter Filter
        $search = $request->input('q');
        $category_id = $request->input('category');
        $status = $request->input('status'); // available, borrowed, maintenance

        // 2. Query Items (Hanya kolom aman)
        $query = Item::select(
            'id',
            'name',
            'brand',
            'type',
            'status',
            'condition',
            'serial_number',
            'image_path' // Kolom ini sudah ada sekarang
        )
            ->with('categories:id,name'); // Eager load kategori (cuma nama & id)

        // 3. Filter Search (Nama / Merk / Tipe)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // 4. Filter Kategori
        if ($category_id) {
            $query->whereHas('categories', function ($q) use ($category_id) {
                $q->where('categories.id', $category_id);
            });
        }

        // 5. Filter Status
        if ($status) {
            if ($status === 'available') {
                $query->where('status', 'available');
            } elseif ($status === 'borrowed') {
                $query->where('status', 'borrowed');
            } elseif ($status === 'maintenance') {
                $query->whereIn('status', ['maintenance', 'broken', 'disposed']); // Grouping status non-aktif
            }
        }

        // 6. Urutkan & Pagination
        $items = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();

        // 7. Ambil Data Kategori untuk Filter Chips
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return view('public.catalog', compact('items', 'categories'));
    }
}
