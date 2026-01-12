<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// PENTING: Import library ini di paling atas!
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $fileName = 'Backup_Inventaris_' . date('Y-m-d_H-i') . '.xlsx';

        // Hitung Tanggal jika ada filter
        [$startDate, $endDate] = $this->getStartEndDates($request);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BackupExport($modules, $startDate, $endDate), $fileName);
    }

    public function backupDatabase()
    {
        try {
            // Ambil creds dari config/env
            $dbName = config('database.connections.mysql.database');
            $userName = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Nama file backup
            $fileName = 'backup_db_' . date('Y-m-d_H-i-s') . '.sql';

            // Pastikan folder storage/app/backups ada
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                if (!mkdir($backupPath, 0755, true) && !is_dir($backupPath)) {
                    throw new \Exception("Gagal membuat folder backup di: $backupPath");
                }
            }

            $filePath = $backupPath . '/' . $fileName;

            // Settings for Mysqldump (Modern Settings)
            $dumpSettings = [
                'compress' => 'None',
                'no-data' => false,
                'add-drop-table' => true,
                'single-transaction' => true,
                'lock-tables' => false,
                'add-locks' => true,
                'extended-insert' => true,
                'disable-keys' => true,
                'skip-triggers' => false,
                'add-drop-trigger' => true,
                'routines' => true,
                'databases' => false,
                'add-drop-database' => false,
                'hex-blob' => true,
                'no-create-info' => false,
                'where' => ''
            ];

            // Initialize dumper
            // DSN format: mysql:host=localhost;dbname=testdb;port=3306
            $dsn = "mysql:host={$host};dbname={$dbName};port={$port}";
            $dumper = new Mysqldump($dsn, $userName, $password, $dumpSettings);

            // Dump to file
            $dumper->start($filePath);

            // Download response
            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Database backup failed: ' . $e->getMessage());
        }
    }

    public function resetDatabase(Request $request)
    {
        Log::info('RESET DATABASE: Attempt started by user ID ' . auth()->id());

        $request->validate([
            'password' => 'required|string',
        ]);

        // 1. Cek Password Admin
        if (!Hash::check($request->password, auth()->user()->password)) {
            Log::warning('RESET DATABASE: Password check failed for user ID ' . auth()->id());
            return back()->with('error', 'Password salah! Penghapusan dibatalkan demi keamanan.');
        }

        // Get Dates for Partial Reset
        [$startDate, $endDate] = $this->getStartEndDates($request);
        $isPartial = ($startDate && $endDate);
        Log::info("RESET DATABASE: Mode " . ($isPartial ? "PARTIAL ($startDate to $endDate)" : "FULL"));

        try {
            Log::info('RESET DATABASE: Password correct. Starting deletion process...');

            // 2. Disable Foreign Key Checks (Generic)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            if ($isPartial) {
                // PARTIAL DELETE (Use Query Builder)
                DB::table('borrowings')->whereBetween('created_at', [$startDate, $endDate])->delete();
                DB::table('room_borrowings')->whereBetween('created_at', [$startDate, $endDate])->delete();
                DB::table('prints')->whereBetween('created_at', [$startDate, $endDate])->delete();
                DB::table('item_out_logs')->whereBetween('created_at', [$startDate, $endDate])->delete();
                DB::table('activity_logs')->whereBetween('created_at', [$startDate, $endDate])->delete();

                if (Schema::hasTable('notifications')) {
                    DB::table('notifications')->whereBetween('created_at', [$startDate, $endDate])->delete();
                }
            } else {
                // FULL DELETE (Raw SQL + Reset Auto Increment)
                // Using Unprepared for multiple statements in one go
                $sql = "
                    DELETE FROM borrowings;
                    ALTER TABLE borrowings AUTO_INCREMENT = 1;
                    DELETE FROM room_borrowings;
                    ALTER TABLE room_borrowings AUTO_INCREMENT = 1;
                    DELETE FROM prints;
                    ALTER TABLE prints AUTO_INCREMENT = 1;
                    DELETE FROM item_out_logs;
                    ALTER TABLE item_out_logs AUTO_INCREMENT = 1;
                    DELETE FROM activity_logs;
                    ALTER TABLE activity_logs AUTO_INCREMENT = 1;
                ";

                if (Schema::hasTable('notifications')) {
                    $sql .= "
                    DELETE FROM notifications;
                    ALTER TABLE notifications AUTO_INCREMENT = 1;
                    ";
                }

                DB::unprepared($sql);
            }

            // 4. Enable kembali
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            Log::info('RESET DATABASE: Deletion successful.');

            $desc = $isPartial
                ? "<span class='text-orange-600 font-bold'>RESET DATA PARSIAL ($startDate s/d $endDate)</span>"
                : "<span class='text-red-600 font-bold'>RESET DATA FULL (SEMUA WAKTU)</span>";

            // 5. Log aktivitas ini
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'RESET DATABASE',
                'model' => 'System',
                'model_id' => 0,
                'description' => $desc,
                'ip_address' => $request->ip()
            ]);

            return back()->with('success', 'Database berhasil di-reset! Data transaksi dan log terpilih telah dihapus.');

        } catch (\Exception $e) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $x) {
            }
            Log::error('RESET DATABASE: Failed with error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mereset database: ' . $e->getMessage());
        }
    }

    public function importItems(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Max 2MB
        ]);

        try {
            $file = $request->file('file');

            // Lakukan Import
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ItemsImport, $file);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'IMPORT',
                'model' => 'Item',
                'model_id' => 0,
                'description' => 'Melakukan Import Data Barang dari Excel',
                'ip_address' => $request->ip()
            ]);

            return back()->with('success', 'Data Barang berhasil di-import!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Gagal Import: Validasi Error pada ';
            foreach ($failures as $failure) {
                $errorMsg .= 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '. ';
            }
            return back()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('IMPORT ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }

    private function getStartEndDates(Request $request)
    {
        $range = $request->input('date_range', 'all');
        $start = null;
        $end = null;

        switch ($range) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'week':
                $start = now()->subWeek()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'month':
                $start = now()->subMonth()->startOfDay();
                $end = now()->endOfDay();
                break;
            case '6months':
                $start = now()->subMonths(6)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'year':
                $start = now()->subYear()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'custom':
                if ($request->filled(['start_date', 'end_date'])) {
                    $start = \Carbon\Carbon::parse($request->start_date)->startOfDay();
                    $end = \Carbon\Carbon::parse($request->end_date)->endOfDay();
                }
                break;
            case 'all':
            default:
                // Start and End remain null
                break;
        }

        return [$start, $end];
    }
}
