<?php

namespace App\Http\Controllers;

use App\Models\Print3D;
use App\Models\Printer;
use App\Models\PeminjamUser;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    // =============================
    // INDEX
    // =============================
    public function index()
    {
        $prints = Print3D::with(['user', 'materialType'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('prints.index', compact('prints'));
    }


    // =============================
    // CREATE FORM
    // =============================
    public function create()
    {
        $users = PeminjamUser::where('is_trained', true)->get();
        $materials = MaterialType::all();
            $printers = Printer::all(); // list mesin
    

        return view('prints.create', compact('users', 'materials', 'printers'));
    }


    // =============================
    // STORE (SAVE PRINT REQUEST)
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:peminjam_users,id',
                'date' => ['required', 'date', function($attribute, $value, $fail) {
        $minDate = \Carbon\Carbon::now()->addDays(2)->startOfDay();
        if (\Carbon\Carbon::parse($value)->lt($minDate)) {
            $fail('Tanggal minimal harus 2 hari dari hari ini.');
        }
    }],
            'start_time'        => 'required',
            'end_time'          => 'required|after:start_time',
            'material_type_id'  => 'nullable|exists:material_types,id',
            'material_amount'   => 'nullable|numeric|min:0',
            'material_unit'     => 'nullable|in:gram,ml',
            'material_source'   => 'nullable|in:lab,penelitian,dosen,pribadi',

            'file_upload'       => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes'             => 'nullable|string',


            
        ]);

        // Cek apakah user sudah ikut pelatihan
        $user = PeminjamUser::find($request->user_id);
        if (!$user->is_trained) {
            return back()->withErrors(['user_id' => 'User ini belum mengikuti pelatihan!']);
        }

        // =============================
        // CEK WAKTU BENTROK
        // =============================
        $overlap = Print3D::where('date', $request->date)
            ->where('printer_id', $request->printer_id)
            ->where(function($q) use ($request){
                $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['start_time' => 'Waktu print bentrok dengan jadwal lain!']);
        }

        // =============================
        // HITUNG DURASI (MENIT)
        // =============================
        $start  = Carbon::parse($request->start_time);
        $end    = Carbon::parse($request->end_time);
        $duration = $start->diffInMinutes($end);

        // =============================
        // HANDLE UPLOAD FILE
        // =============================
        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file_upload')) {
            $fileName = time() . '_' . $request->file_upload->getClientOriginalName();
            $filePath = $request->file_upload->storeAs('prints', $fileName, 'public');
        }

        // =============================
        // SAVE DATABASE
        // =============================
        Print3D::create([
            'user_id'          => $request->user_id,
            'printer_id'       => $request->printer_id,   // ⬅ WAJIB
            'date'             => $request->date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,

            'material_type_id' => $request->material_type_id,
            'material_amount'  => $request->material_amount,
            'material_unit'    => $request->material_unit,
            'material_source'  => $request->material_source,

            'notes'            => $request->notes,
            'file_name'        => $fileName,
            'file_path'        => $filePath,
            'status'           => 'pending',
        ]);


        return redirect()->route('prints.index')
                         ->with('success', 'Request print berhasil dibuat!');

                         
    }


    // =============================
    // EDIT
    // =============================
    public function edit($id)
    {
        $print = Print3D::findOrFail($id);
        $users = PeminjamUser::all();
        $materials = MaterialType::all();
;

        return view('prints.edit', compact('print', 'users', 'materials'));
    }

    // =============================
    // UPDATE
    // =============================
public function update(Request $request, Print3D $print)
{
    $request->validate([
        'status' => 'required|in:pending,printing,done,canceled',
    ]);

    DB::transaction(function () use ($request, $print) {

        // 🔒 LOCK ROW (ANTI DOBEL REQUEST)
        $print = Print3D::where('id', $print->id)
            ->lockForUpdate()
            ->first();

        $oldStatus = $print->status;
        $newStatus = $request->status;

        // =============================
        // POTONG MATERIAL (PENDING → PRINTING)
        // =============================
        if (
            $oldStatus === 'pending' &&
            $newStatus === 'printing' &&
            !$print->material_deducted &&
            $print->material_type_id
        ) {
            $material = MaterialType::findOrFail($print->material_type_id);

            if ($material->stock_balance < $print->material_amount) {
                throw new \Exception('Stock material tidak mencukupi.');
            }

            $material->decrement('stock_balance', $print->material_amount);
            $print->material_deducted = true;
        }

        // =============================
        // REFUND MATERIAL (CANCELED)
        // =============================
        if (
            in_array($oldStatus, ['pending', 'printing']) &&
            $newStatus === 'canceled' &&
            $print->material_deducted &&
            $print->material_type_id
        ) {
            $material = MaterialType::find($print->material_type_id);

            if ($material) {
                $material->increment('stock_balance', $print->material_amount);
            }

            $print->material_deducted = false;
        }

        // =============================
        // UPDATE STATUS
        // =============================
        $print->status = $newStatus;
        $print->save();
    });

    return redirect()->route('prints.index')
        ->with('success', 'Status print berhasil diperbarui.');
}




    // =============================
    // DELETE
    // =============================
    public function destroy($id)
    {
        $print = Print3D::findOrFail($id);

        if ($print->file_path) {
            Storage::disk('public')->delete($print->file_path);
        }

        $print->delete();

        return redirect()->route('prints.index')
                         ->with('success', 'Data berhasil dihapus.');
    }


    public function show($id)
{
    $print = Print3D::findOrFail($id);

    return view('prints.show', compact('print'));
}

public function downloadFile($id)
{
    $print = Print3D::findOrFail($id);

    if (!$print->file_path || !Storage::disk('public')->exists($print->file_path)) {
        abort(404);
    }

    return Storage::disk('public')->download($print->file_path);
}


}
