<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('superadmin.logs.index', compact('logs'));
    }

    // Hapus Satu Log
    public function destroy($id)
    {
        // Hanya Superadmin yang boleh hapus (Double Check)
        if (auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus log.');
        }

        ActivityLog::findOrFail($id)->delete();
        return back()->with('success', 'Log berhasil dihapus.');
    }

    // Bersihkan Semua Log (Optional tapi berguna)
    public function destroyAll()
    {
        if (auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        ActivityLog::truncate(); // Hapus semua isi tabel
        return back()->with('success', 'Seluruh riwayat log berhasil dibersihkan.');
    }
}