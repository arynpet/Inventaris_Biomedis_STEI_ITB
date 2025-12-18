<?php

namespace App\Http\Controllers;

use App\Models\MaterialType;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller
{
    public function index()
    {
        $materials = MaterialType::orderBy('category')->orderBy('name')->get();
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
$request->validate([
    'category'       => 'required|in:filament,resin',
    'name'           => 'required|string|max:255',
    'stock_balance'  => 'required|numeric|min:0',
    'unit'           => 'required|in:gr,ml',
]);


MaterialType::create([
    'category'      => $request->category,
    'name'          => $request->name,
    'stock_balance' => $request->stock_balance,
    'unit'          => $request->unit,
]);

        return redirect()->route('materials.index')
            ->with('success', 'Material type berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $material = MaterialType::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|in:filament,resin',
            'name'     => 'required|string|max:255',
        ]);

        $material = MaterialType::findOrFail($id);
        $material->update($request->only('category', 'name'));

        return redirect()->route('materials.index')
            ->with('success', 'Material type berhasil diupdate!');
    }

    public function destroy($id)
    {
        $material = MaterialType::findOrFail($id);
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material type berhasil dihapus!');
    }

    public function addStock(Request $request, $id)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
    ]);

    $material = MaterialType::findOrFail($id);
    $material->increment('stock_balance', $request->amount);

    return back()->with('success', 'Stock berhasil ditambahkan.');
}

}
