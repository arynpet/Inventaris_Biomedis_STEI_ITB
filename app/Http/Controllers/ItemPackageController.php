<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ItemPackage;
use App\Models\Item;
use Illuminate\Validation\Rule;

class ItemPackageController extends Controller
{
    public function index()
    {
        $packages = ItemPackage::withCount('items')->latest()->paginate(10);
        return view('item_packages.index', compact('packages'));
    }

    public function create()
    {
        // Ambil item yang kategorinya mengandung 'Praktikum' DAN belum punya paket
        $availableItems = Item::whereHas('categories', function ($query) {
            $query->where('name', 'LIKE', '%Praktikum%');
        })
            ->whereNull('item_package_id')
            ->orderBy('name')
            ->get();

        return view('item_packages.create', compact('availableItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items,id',
        ]);

        $package = ItemPackage::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'available',
        ]);

        // Update items to belong to this package
        Item::whereIn('id', $request->item_ids)->update(['item_package_id' => $package->id]);

        return redirect()->route('item-packages.index')
            ->with('success', 'Paket Praktikum berhasil dibuat dengan ' . count($request->item_ids) . ' item.');
    }

    public function show(ItemPackage $itemPackage)
    {
        $itemPackage->load('items.categories');
        return view('item_packages.show', compact('itemPackage'));
    }

    public function edit(ItemPackage $itemPackage)
    {
        // Items currently in this package
        $currentItems = $itemPackage->items;

        // Items available to be added (Praktikum category & No Package)
        $availableItems = Item::whereHas('categories', function ($query) {
            $query->where('name', 'LIKE', '%Praktikum%');
        })
            ->whereNull('item_package_id')
            ->orderBy('name')
            ->get();

        // Merge: Show current items + available items
        $allItems = $currentItems->merge($availableItems);

        return view('item_packages.edit', compact('itemPackage', 'allItems', 'currentItems'));
    }

    public function update(Request $request, ItemPackage $itemPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'item_ids' => 'sometimes|array',
            'item_ids.*' => 'exists:items,id',
        ]);

        $itemPackage->update($request->only('name', 'description'));

        // 1. Release all items currently in this package
        $itemPackage->items()->update(['item_package_id' => null]);

        // 2. Assign selected items
        if ($request->has('item_ids')) {
            Item::whereIn('id', $request->item_ids)->update(['item_package_id' => $itemPackage->id]);
        }

        return redirect()->route('item-packages.index')
            ->with('success', 'Paket Praktikum berhasil diperbarui.');
    }

    public function destroy(ItemPackage $itemPackage)
    {
        // Items are automatically set to null due to DB constraint, but for clarity/safety:
        $itemPackage->items()->update(['item_package_id' => null]);

        $itemPackage->delete();

        return redirect()->route('item-packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
