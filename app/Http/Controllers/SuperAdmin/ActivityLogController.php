<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User; // Import User
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Siapkan Query Dasar
        $query = ActivityLog::with('user');

        // 2. Filter: Search (Deskripsi atau Model ID)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                    ->orWhere('model_id', 'like', "%$search%")
                    ->orWhere('model', 'like', "%$search%");
            });
        }

        // 3. Filter: Action (Create, Update, Delete)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // 4. Filter: User Specific
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 5. Sorting (Default: Created At Descending)
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Validasi kolom agar tidak error jika user ubah URL manual
        $allowedSorts = ['created_at', 'action', 'model'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->latest();
        }

        // 6. Eksekusi Pagination
        $logs = $query->paginate(20)->withQueryString(); // withQueryString agar filter tidak hilang saat pindah hal

        // Data untuk Dropdown Filter
        $users = User::orderBy('name')->get();
        $actions = ['create', 'update', 'delete', 'login', 'logout', 'restore']; // Sesuaikan dengan sistemmu

        return view('superadmin.logs.index', compact('logs', 'users', 'actions'));
    }

    public function history($model, $id)
    {
        // Cari objek aslinya untuk info header (opsional)
        $fullModelPath = "App\\Models\\" . $model;
        $targetObject = null;
        if (class_exists($fullModelPath)) {
            $targetObject = $fullModelPath::find($id);
        }

        // Ambil semua log yang terkait dengan Model dan ID tertentu
        $logs = ActivityLog::with('user')
            ->where('model', $model)
            ->where('model_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.logs.history', compact('logs', 'model', 'id', 'targetObject'));
    }

    // ... (Fungsi destroy dan destroyAll tetap sama seperti kode kamu) ...
    public function destroy($id)
    {
        if (auth()->user()->role !== 'superadmin')
            return back()->with('error', 'Akses ditolak.');
        ActivityLog::findOrFail($id)->delete();
        return back()->with('success', 'Log berhasil dihapus.');
    }

    public function destroyAll()
    {
        if (auth()->user()->role !== 'superadmin')
            return back()->with('error', 'Akses ditolak.');
        ActivityLog::truncate();
        return back()->with('success', 'Seluruh riwayat log berhasil dibersihkan.');
    }
}