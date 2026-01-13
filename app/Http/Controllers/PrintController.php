<?php

namespace App\Http\Controllers;

use App\Models\Print3D;
use App\Models\Printer;
use App\Models\PeminjamUser;
use App\Models\MaterialType;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrintController extends Controller
{
    // =============================
    // INDEX (Ongoing: Pending/Printing)
    // =============================
    public function index(Request $request)
    {
        // 1. Ambil Input Filter
        $search = $request->input('search');
        $status = $request->input('status'); // pending, printing

        // 2. Query Builder
        // HANYA ambil status 'pending' dan 'printing' (Ongoing)
        $query = Print3D::with(['user', 'materialType', 'printer'])
            ->whereIn('status', ['pending', 'printing']);

        // Filter Search (User, Material, atau Printer)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('materialType', function($m) use ($search) {
                    $m->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('printer', function($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter Status Spesifik (Pending/Printing)
        if ($status) {
            $query->where('status', $status);
        }

        $prints = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();

        return view('prints.index', compact('prints'));
    }

    // =============================
    // HISTORY (Done/Canceled)
    // =============================
    public function history(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // done, canceled

        // HANYA ambil status 'done' dan 'canceled' (Arsip)
        $query = Print3D::with(['user', 'materialType', 'printer'])
            ->whereIn('status', ['done', 'canceled']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('materialType', function($m) use ($search) {
                    $m->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $histories = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        return view('prints.history', compact('histories'));
    }

    // =============================
    // CREATE FORM
    // =============================
    public function create()
    {
        // Hanya user yang sudah 'trained' (terlatih)
        $users = PeminjamUser::where('is_trained', true)->orderBy('name')->get();
        $materials = MaterialType::orderBy('name')->get();
        $printers = Printer::orderBy('name')->get();
    
        return view('prints.create', compact('users', 'materials', 'printers'));
    }

    // =============================
    // STORE (SAVE PRINT REQUEST)
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:peminjam_users,id',
            'project_name'      => 'required|string|max:255', 
            'printer_id'        => 'required|exists:printers,id',
            'date'              => ['required', 'date', function($attribute, $value, $fail) {
                // Aturan: Minimal booking H+2 dari hari ini
                $days = config('services.print3d.min_booking_days'); 
                $minDate = Carbon::now()->addDays($days)->startOfDay();
                if (Carbon::parse($value)->lt($minDate)) {
                    $fail('Tanggal minimal harus ' . $days . ' hari dari hari ini.');
                }
            }],
            'start_time'        => 'required',
            'end_time'          => 'required|after:start_time',
            'material_type_id'  => 'nullable|exists:material_types,id',
            'material_amount'   => 'nullable|numeric|min:0',
            'material_unit'     => 'nullable|in:gram,mililiter',
            'material_source'   => 'nullable|in:lab,penelitian,dosen,pribadi',
            'file_upload'       => 'nullable|mimes:pdf,jpg,jpeg,png,stl,obj|max:10240', // Max 10MB
            'notes'             => 'nullable|string',
        ]);

        $user = PeminjamUser::find($request->user_id);
        if (!$user->is_trained) {
            return back()->withErrors(['user_id' => 'User ini belum mengikuti pelatihan (Training)!']);
        }

        // Cek Bentrok Jadwal
        $overlap = Print3D::where('date', $request->date)
            ->where('printer_id', $request->printer_id)
            ->where(function($q) use ($request){
                $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['start_time' => 'Waktu print bentrok dengan jadwal lain di mesin ini!']);
        }

        // Upload File
        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file_upload')) {
            $originalName = $request->file_upload->getClientOriginalName();
            $extension = $request->file_upload->extension();
            $safeName = \Illuminate\Support\Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $fileName = time() . '_' . $safeName . '.' . $extension;
            $filePath = $request->file_upload->storeAs('prints', $fileName, 'public');
        }

        Print3D::create([
            'user_id'           => $request->user_id,
            'project_name'      => $request->project_name,
            'printer_id'        => $request->printer_id,
            'date'              => $request->date,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'material_type_id'  => $request->material_type_id,
            'material_amount'   => $request->material_amount,
            'material_unit'     => $request->material_unit,
            'material_source'   => $request->material_source,
            'notes'             => $request->notes,
            'file_name'         => $fileName,
            'file_path'         => $filePath,
            'status'            => 'pending',
        ]);

        return redirect()->route('prints.index')
            ->with('success', 'Request print berhasil dibuat! Menunggu persetujuan.');
    }

    // =============================
    // EDIT
    // =============================
    public function edit($id)
    {
        $print = Print3D::findOrFail($id);
        
        // Cek jika sudah selesai/batal, tidak boleh diedit sembarangan
        if (in_array($print->status, ['done', 'canceled'])) {
            return redirect()->route('prints.history')->with('error', 'Data arsip tidak dapat diedit.');
        }

        $users = PeminjamUser::where('is_trained', true)->get();
        $materials = MaterialType::all();
        $printers = Printer::all();

        return view('prints.edit', compact('print', 'users', 'materials', 'printers'));
    }

    // =============================
    // UPDATE (Status Change Logic)
    // =============================
    public function update(Request $request, Print3D $print)
    {
        // Validasi status yang diperbolehkan
        $request->validate([
            'status' => 'required|in:pending,printing,done,canceled',
        ]);

        $oldStatus = $print->status;
        $newStatus = $request->status;

        // Validasi: Cegah invalid status transitions
        // Contoh: done/canceled tidak bisa kembali ke pending
        if (in_array($oldStatus, ['done', 'canceled']) && in_array($newStatus, ['pending', 'printing'])) {
            return back()->withErrors(['status' => 'Status ' . $oldStatus . ' tidak dapat diubah ke ' . $newStatus]);
        }

        try {
            DB::transaction(function () use ($request, $print, $oldStatus, $newStatus) {
                // Lock row untuk mencegah race condition
                $print = Print3D::where('id', $print->id)->lockForUpdate()->first();

                // LOGIC 1: POTONG MATERIAL (Pending -> Printing)
                // Hanya jika material dari LAB
                if (
                    $oldStatus === 'pending' &&
                    $newStatus === 'printing' &&
                    !$print->material_deducted &&
                    $print->material_type_id &&
                    $print->material_source === 'lab'
                ) {
                    $material = MaterialType::where('id', $print->material_type_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$material) {
                        throw ValidationException::withMessages([
                            'material' => 'Material tidak ditemukan.'
                        ]);
                    }

                    if ($material->stock_balance < $print->material_amount) {
                        throw ValidationException::withMessages([
                            'stock' => "Stok material '{$material->name}' tidak mencukupi (Sisa: {$material->stock_balance})."
                        ]);
                    }

                    $material->decrement('stock_balance', $print->material_amount);
                    $print->material_deducted = true;
                }

                // LOGIC 2: REFUND MATERIAL (Canceled)
                // Jika sudah dipotong tapi dibatalkan, kembalikan stok
                if (
                    in_array($oldStatus, ['pending', 'printing']) &&
                    $newStatus === 'canceled' &&
                    $print->material_deducted &&
                    $print->material_type_id &&
                    $print->material_source === 'lab'
                ) {
                    $material = MaterialType::where('id', $print->material_type_id)
                        ->lockForUpdate()
                        ->first();
                    
                    if ($material) {
                        $material->increment('stock_balance', $print->material_amount);
                    }
                    $print->material_deducted = false;
                }

                // Update Status
                $print->status = $newStatus;
                
                // Update data lain jika ada di request (opsional, untuk edit detail)
                $print->fill($request->except(['status', '_token', '_method']));
                
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'model' => 'Print3D',
                    'model_id' => $print->id,
                    'description' => "Status changed: {$oldStatus} → {$newStatus} for '{$print->project_name}'",
                    'ip_address' => request()->ip(),
                ]);

                $print->save();
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }

        // Redirect cerdas: Jika status jadi 'done'/'canceled', lempar ke history
        if (in_array($request->status, ['done', 'canceled'])) {
            return redirect()->route('prints.history')->with('success', 'Print selesai/dibatalkan. Data dipindahkan ke Riwayat.');
        }

        return redirect()->route('prints.index')->with('success', 'Status print berhasil diperbarui.');
    }

    // =============================
    // DELETE
    // =============================
    public function destroy($id)
    {
        $print = Print3D::findOrFail($id);

        // Hapus file fisik
        if ($print->file_path && Storage::disk('public')->exists($print->file_path)) {
            Storage::disk('public')->delete($print->file_path);
        }

        $print->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    // =============================
    // SHOW & DOWNLOAD
    // =============================
    public function show($id)
    {
        $print = Print3D::with(['user', 'materialType', 'printer'])->findOrFail($id);
        return view('prints.show', compact('print'));
    }

    public function downloadFile($id)
    {
        $print = Print3D::findOrFail($id);

        if (!$print->file_path || !Storage::disk('public')->exists($print->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($print->file_path, $print->file_name);
    }
}