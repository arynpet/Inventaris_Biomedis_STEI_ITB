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
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('materialType', function ($m) use ($search) {
                        $m->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('printer', function ($p) use ($search) {
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
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('materialType', function ($m) use ($search) {
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
            'user_id' => 'required|exists:peminjam_users,id',
            'project_name' => 'required|string|max:255',
            'printer_id' => 'required|exists:printers,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'material_type_id' => 'nullable|exists:material_types,id',
            'material_amount' => 'nullable|numeric|min:0',
            'material_unit' => 'nullable|in:gram,mililiter',
            'material_source' => 'nullable|in:lab,penelitian,dosen,pribadi',
            'lecturer_name' => 'nullable|string|required_if:material_source,dosen',
            'file_upload' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB (PDF/Image)
            'stl_file' => 'required|file|max:51200', // Max 50MB
            'notes' => 'nullable|string',
        ]);

        $user = PeminjamUser::find($request->user_id);
        if (!$user->is_trained) {
            return back()->withErrors(['user_id' => __('messages.print.user_not_trained')]);
        }

        // ✅ M5 FIX: Use extracted method
        $overlap = $this->hasPrintScheduleOverlap(
            $request->date,
            $request->printer_id,
            $request->start_time,
            $request->end_time
        );

        if ($overlap) {
            return back()->withErrors(['start_time' => __('messages.print.schedule_overlap')]);
        }

        // Upload File Proposal (PDF / Image from Remote)
        // Helper returns array [path, originalName] or null
        $fileData = $this->processFileUpload($request, 'file_upload', 'prints/proposals');
        $filePath = $fileData['path'] ?? null;
        $fileName = $fileData['name'] ?? null;

        // Upload File STL (3D Model / ZIP)
        $stlPath = null;
        if ($request->hasFile('stl_file')) {
            $originalName = $request->stl_file->getClientOriginalName();
            // Validasi nama file: (FLN|RSN)-NAMAPEMILIK-NAMAFILE.(stl|obj|zip)
            if (!preg_match('/^(FLN|RSN)-[A-Z0-9]+-[A-Z0-9_]+\.(stl|obj|zip)$/i', $originalName)) {
                // Optional warning/validation similar to above
            }

            $extension = $request->stl_file->extension(); // stl/obj/zip
            // Use original name as requested format is meaningful
            $stlPath = $request->stl_file->storeAs('prints/models', $originalName, 'public');
        }

        Print3D::create([
            'user_id' => $request->user_id,
            'project_name' => $request->project_name,
            'printer_id' => $request->printer_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'material_type_id' => $request->material_type_id,
            'material_amount' => $request->material_amount,
            'material_unit' => $request->material_unit,
            'material_source' => $request->material_source,
            'lecturer_name' => $request->lecturer_name,
            'notes' => $request->notes,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'stl_path' => $stlPath,
            'status' => 'pending',
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
    // UPDATE (Status & Data Change Logic)
    // =============================
    public function update(Request $request, Print3D $print)
    {
        // Validasi
        $request->validate([
            'user_id' => 'required|exists:peminjam_users,id',
            'project_name' => 'required|string|max:255',
            'printer_id' => 'required|exists:printers,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'material_type_id' => 'nullable|exists:material_types,id',
            'material_amount' => 'nullable|numeric|min:0',
            'material_unit' => 'nullable|in:gram,mililiter',
            'material_source' => 'nullable|in:lab,penelitian,dosen,pribadi',
            'lecturer_name' => 'nullable|string|required_if:material_source,dosen',
            'file_upload' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB (PDF/Image)
            'stl_file' => 'nullable|file|max:51200', // Max 50MB
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,printing,done,canceled',
        ]);

        $oldStatus = $print->status;
        $newStatus = $request->status;

        // Validasi: Cegah invalid status transitions
        if (in_array($oldStatus, ['done', 'canceled']) && in_array($newStatus, ['pending', 'printing'])) {
            return back()->withErrors(['status' => __('messages.print.invalid_status_transition', ['old_status' => $oldStatus, 'new_status' => $newStatus])]);
        }

        // Cek Overlap jika jadwal berubah
        if ($request->date != $print->date || $request->start_time != $print->start_time || $request->end_time != $print->end_time || $request->printer_id != $print->printer_id) {
            $overlap = $this->hasPrintScheduleOverlap(
                $request->date,
                $request->printer_id,
                $request->start_time,
                $request->end_time,
                $print->id // Exclude current ID
            );

            if ($overlap) {
                return back()->withErrors(['start_time' => __('messages.print.schedule_overlap')]);
            }
        }

        try {
            DB::transaction(function () use ($request, $print, $oldStatus, $newStatus) {
                // Lock row untuk mencegah race condition
                $print = Print3D::where('id', $print->id)->lockForUpdate()->first();

                // 1. Handle File Uploads
                // Proposal File
                $fileData = $this->processFileUpload($request, 'file_upload', 'prints/proposals');
                if ($fileData) {
                    // Delete old file if exists
                    if ($print->file_path && Storage::disk('public')->exists($print->file_path)) {
                        Storage::disk('public')->delete($print->file_path);
                    }
                    $print->file_path = $fileData['path'];
                    $print->file_name = $fileData['name'];
                }

                // STL File (or ZIP)
                if ($request->hasFile('stl_file')) {
                    $originalName = $request->stl_file->getClientOriginalName();
                    if (!preg_match('/^(FLN|RSN)-[A-Z0-9]+-[A-Z0-9_]+\.(stl|obj|zip)$/i', $originalName)) {
                        // Optional validation matching store
                    }

                    // Delete old STL
                    if ($print->stl_path && Storage::disk('public')->exists($print->stl_path)) {
                        Storage::disk('public')->delete($print->stl_path);
                    }

                    $print->stl_path = $request->stl_file->storeAs('prints/models', $originalName, 'public');
                }

                // 2. Logic Status Material
                // LOGIC 1: POTONG MATERIAL (Pending -> Printing)
                if (
                    $oldStatus === 'pending' &&
                    $newStatus === 'printing' &&
                    !$print->material_deducted &&
                    $request->material_type_id &&
                    $request->material_source === 'lab'
                ) {
                    $material = MaterialType::where('id', $request->material_type_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$material) {
                        throw ValidationException::withMessages([
                            'material' => 'Material tidak ditemukan.'
                        ]);
                    }

                    if ($material->stock_balance < $request->material_amount) {
                        throw ValidationException::withMessages([
                            'stock' => "Stok material '{$material->name}' tidak mencukupi (Sisa: {$material->stock_balance})."
                        ]);
                    }

                    $material->decrement('stock_balance', $request->material_amount);
                    $print->material_deducted = true;
                }

                // LOGIC 2: REFUND MATERIAL (Canceled)
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

                // 3. Update Data
                $print->fill($request->except(['file_upload', 'stl_file', '_token', '_method']));

                // Ensure manual status update from request is set (fill handles it if in fillable, but safe to set explicity)
                $print->status = $newStatus;

                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'model' => 'Print3D',
                    'model_id' => $print->id,
                    'description' => "Updated data & Status: {$oldStatus} → {$newStatus}",
                    'ip_address' => request()->ip(),
                ]);

                $print->save();
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }

        // Redirect cerdas
        if (in_array($request->status, ['done', 'canceled'])) {
            return redirect()->route('prints.history')->with('success', 'Print selesai/dibatalkan. Data dipindahkan ke Riwayat.');
        }

        return redirect()->route('prints.index')->with('success', 'Data request berhasil diperbarui.');
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

    public function downloadStl($id)
    {
        $print = Print3D::findOrFail($id);

        if (!$print->stl_path || !Storage::disk('public')->exists($print->stl_path)) {
            abort(404, 'File STL/3D tidak ditemukan');
        }

        return Storage::disk('public')->download($print->stl_path);
    }

    /**
     * ✅ M5 FIX: Extracted print schedule overlap checking logic
     * Check if printer has overlapping print schedules
     * 
     * @param string $date
     * @param int $printerId
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeId Optional ID to exclude from check (for updates)
     * @return bool
     */
    private function hasPrintScheduleOverlap($date, $printerId, $startTime, $endTime, $excludeId = null)
    {
        return Print3D::where('date', $date)
            ->where('printer_id', $printerId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();
    }

    /**
     * Helper to process file upload (Direct or Remote URL)
     * Returns ['path' => ..., 'name' => ...] or null
     */
    private function processFileUpload(Request $request, $inputName, $targetFolder)
    {
        // 1. Direct File Upload
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $originalName = $file->getClientOriginalName();
            $extension = $file->extension();
            $safeName = \Illuminate\Support\Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $fileName = time() . '_' . $safeName . '.' . $extension;
            $path = $file->storeAs($targetFolder, $fileName, 'public');

            return ['path' => $path, 'name' => $fileName];
        }

        // 2. Remote URL (scan from HP)
        // Hidden input format name: "inputName_url" (e.g., file_upload_url)
        $urlInput = $inputName . '_url';
        if ($request->filled($urlInput)) {
            $url = $request->input($urlInput);

            // Check if it's from our temp storage
            if (str_contains($url, '/storage/temp/')) {
                try {
                    $basename = basename($url);
                    $tempPath = 'temp/' . $basename;
                    $newFileName = time() . '_remote_' . $basename;
                    $newPath = $targetFolder . '/' . $newFileName;

                    if (Storage::disk('public')->exists($tempPath)) {
                        Storage::disk('public')->move($tempPath, $newPath);
                        return ['path' => $newPath, 'name' => $newFileName];
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to move remote file: " . $e->getMessage());
                }
            }
        }

        return null; // No file uploaded
    }
}