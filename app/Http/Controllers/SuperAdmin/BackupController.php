<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Models
use App\Models\Item;
use App\Models\Room;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\ItemOutLog;

class BackupController extends Controller
{
    public function index()
    {
        return view('superadmin.backup.index');
    }

    public function download(Request $request)
    {
        $modules = $request->input('modules', []);

        if (empty($modules)) {
            return back()->with('error', 'Pilih minimal satu modul untuk dibackup.');
        }

        // Gunakan Library Excel untuk export (One-Click Solution)
        // File akan berisi Multiple Sheets sesuai modul yang dipilih
        $fileName = 'Full_Backup_Inventaris_' . date('Y-m-d_H-i') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BackupExport($modules), $fileName);
    }
}
