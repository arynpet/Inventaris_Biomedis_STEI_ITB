<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $printers = Printer::orderBy('name')->get();
        return view('printers.index', compact('printers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('printers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:filament,resin',   // <-- ADD
            'description' => 'nullable|string',
            'status' => 'required|in:available,in_use,maintenance',
            'available_at' => 'nullable|date',
        ]);

        $data = $request->only(['name', 'category', 'description', 'status', 'available_at']);
        $data['material_type_id'] = $request->category;

        Printer::create($data);

        return redirect()->route('printers.index')
            ->with('success', 'Printer berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Printer $printer)
    {
        return view('printers.show', compact('printer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Printer $printer)
    {
        return view('printers.edit', compact('printer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Printer $printer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:filament,resin',  // <-- ADD
            'description' => 'nullable|string',
            'status' => 'required|in:available,in_use,maintenance',
            'available_at' => 'nullable|date',
        ]);

        $printer->update($request->only([
            'name',
            'category',
            'description',
            'status',
            'available_at',
        ]));

        return redirect()->route('printers.index')
            ->with('success', 'Printer berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect()->route('printers.index')
            ->with('success', 'Printer berhasil dihapus!');
    }

    /**
     * Bulk Action
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $action = $request->input('action_type');

        if (empty($ids))
            return back()->with('error', 'Tidak ada printer dipilih.');

        if ($action === 'delete') {
            Printer::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' printer berhasil dihapus.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
