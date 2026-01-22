<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeminjamUserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\RoomBorrowingController;
use App\Http\Controllers\MaterialTypeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\NaraController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

// Debug Route (Remove in production)
Route::get('/debug-remote', function () {
    return view('debug-remote');
})->middleware('auth');

// Public Catalog
// Public Catalog
Route::get('/katalog', [App\Http\Controllers\PublicCatalogController::class, 'index'])->name('public.catalog');

// Student Auth (Public)
Route::post('/student/login', [App\Http\Controllers\StudentAuthController::class, 'authenticate'])->name('student.login');
Route::post('/student/register', [App\Http\Controllers\StudentAuthController::class, 'store'])->name('student.register');
Route::post('/student/logout', [App\Http\Controllers\StudentAuthController::class, 'logout'])->name('student.logout');

/*
|--------------------------------------------------------------------------
| Dashboard & Protected Routes (All Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 0. AI ASSISTANT (N.A.R.A)
    // ====================================================
    Route::prefix('nara')->middleware('throttle:30,1')->name('nara.')->group(function () {
        Route::post('/chat', [NaraController::class, 'ask'])->name('chat');
        Route::post('/destroy', [NaraController::class, 'destroyAsset'])->name('destroy');
        Route::post('/store-batch', [NaraController::class, 'storeBatch'])->name('store_batch');
    });

    // ====================================================
    // 1. DASHBOARD
    // ====================================================
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Panduan Sistem / SOP
    Route::get('/tutorial', [App\Http\Controllers\GuideController::class, 'index'])->name('guide.index');
    Route::get('/panduan-praktis', [App\Http\Controllers\GuideController::class, 'scenarios'])->name('guide.scenarios');

    // ====================================================
    // 2. PROFILE MANAGEMENT
    // ====================================================
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/dev-mode', [ProfileController::class, 'updateDevMode'])->name('profile.dev_mode'); // <--- Toggle Dev Mode
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ====================================================
    // 2.5. DEVELOPER TOOLS
    // ====================================================
    Route::prefix('dev')->name('dev.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DevController::class, 'index'])->name('index');
        Route::post('/reset-database', [App\Http\Controllers\DevController::class, 'resetDatabase'])->name('reset_db');
    });

    // ====================================================
    // 2.6. REPORTS & EXPORTS
    // ====================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/items/excel', [App\Http\Controllers\ReportController::class, 'exportItemsExcel'])->name('items.excel');
        Route::post('/monthly/pdf', [App\Http\Controllers\ReportController::class, 'monthlyLoanPdf'])->name('monthly.pdf');
        Route::get('/item-condition/pdf', [App\Http\Controllers\ReportController::class, 'itemConditionPdf'])->name('condition.pdf');
    });

    // ====================================================
    // 3. INVENTORY MANAGEMENT (ITEMS)
    // ====================================================
    // Trash & Restore
    Route::get('/items/trash', [ItemController::class, 'trash'])->name('items.trash');
    Route::get('/items/action/bulk-restore', [ItemController::class, 'bulkRestore'])->name('items.bulk_restore');
    Route::delete('/items/action/bulk-terminate', [ItemController::class, 'bulkTerminate'])->name('items.bulk_terminate');
    Route::get('/items/{id}/restore', [ItemController::class, 'restore'])->name('items.restore');
    Route::delete('/items/{id}/terminate', [ItemController::class, 'terminate'])->name('items.terminate'); // Hard Delete

    // Bulk Actions & QR
    Route::post('items/regenerate-qr', [ItemController::class, 'regenerateAllQr'])->name('items.regenerate_qr');
    Route::post('items/bulk-action', [ItemController::class, 'bulkAction'])->name('items.bulk_action');
    Route::post('items/bulk-update', [ItemController::class, 'bulkUpdate'])->name('items.bulk_update');
    Route::get('/items/{item}/qr-pdf', [ItemController::class, 'qrPdf'])->name('items.qr.pdf');
    Route::post('/items/print-bulk-qr', [ItemController::class, 'printBulkQr'])->name('items.print_bulk_qr');
    Route::get('/api/items/by-qr/{qr}', [ItemController::class, 'findByQr'])->middleware('throttle:60,1'); // API internal

    // Barang Keluar (Logs)
    Route::prefix('items-management')->group(function () {
        Route::get('out-logs', [ItemController::class, 'outIndex'])->name('items.out.index');
        Route::get('out/{item}/create', [ItemController::class, 'outCreate'])->name('items.out.create');
        Route::post('out/{item}', [ItemController::class, 'outStore'])->name('items.out.store');
        Route::get('out/{item}/pdf', [ItemController::class, 'outPdf'])->name('items.out.pdf');
    });

    // Remote Camera / Scan to Upload
    Route::get('/remote-upload/token', [App\Http\Controllers\RemoteUploadController::class, 'generateToken'])->name('remote.token');

    // Resource Items (Harus di bawah route custom items)
    Route::resource('items', ItemController::class);

    // ====================================================
    // 3.5. MAINTENANCE & CALIBRATION
    // ====================================================
    Route::resource('maintenances', App\Http\Controllers\MaintenanceController::class);
    Route::post('/maintenances/{maintenance}/start', [App\Http\Controllers\MaintenanceController::class, 'start'])->name('maintenances.start');
    Route::post('/maintenances/{maintenance}/complete', [App\Http\Controllers\MaintenanceController::class, 'complete'])->name('maintenances.complete');

    // ====================================================
    // 4. MASTER DATA
    // ====================================================
    // Rooms
    Route::post('rooms/bulk-action', [RoomController::class, 'bulkAction'])->name('rooms.bulk_action');
    Route::post('/rooms/move-item', [RoomController::class, 'moveItem'])->name('rooms.moveItem');
    Route::resource('rooms', RoomController::class);

    // Categories
    Route::post('/bulk-ops/categories', [CategoryController::class, 'bulkAction'])->name('categories.bulk_action');
    Route::resource('categories', CategoryController::class);

    // Materials (Stok 3D Print)
    Route::post('/materials/{id}/add-stock', [MaterialTypeController::class, 'addStock'])->name('materials.addStock');
    Route::post('/materials/bulk-action', [MaterialTypeController::class, 'bulkAction'])->name('materials.bulk_action');
    Route::resource('materials', MaterialTypeController::class);

    // ====================================================
    // 5. PEMINJAMAN ALAT (BORROWINGS)
    // ====================================================
    Route::post('/borrowings/bulk-return', [BorrowingController::class, 'bulkReturn'])->name('borrowings.bulk_return');
    Route::get('/borrowings/history', [BorrowingController::class, 'history'])->name('borrowings.history');
    Route::get('/borrowings/history/pdf', [BorrowingController::class, 'historyPdf'])->name('borrowings.historyPdf');
    Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');
    Route::post('/borrowings/scan-qr', [BorrowingController::class, 'scan'])->name('borrowings.scan')->middleware('throttle:60,1');
    Route::get('/borrowings/{id}/pdf', [BorrowingController::class, 'pdf'])->name('borrowings.pdf');
    Route::resource('borrowings', BorrowingController::class);

    // ====================================================
    // 6. PEMINJAMAN RUANGAN
    // ====================================================
    Route::post('/room_borrowings/bulk-action', [RoomBorrowingController::class, 'bulkAction'])->name('room_borrowings.bulk_action');
    Route::get('/room_borrowings/history', [RoomBorrowingController::class, 'history'])->name('room_borrowings.history');
    Route::put('/room_borrowings/{id}/approve', [RoomBorrowingController::class, 'approveRoom'])->name('room_borrowings.approve');
    Route::put('/room_borrowings/{id}/return', [RoomBorrowingController::class, 'returnRoom'])->name('room_borrowings.return');
    Route::resource('room_borrowings', RoomBorrowingController::class);

    // ====================================================
    // 7. 3D PRINTING SERVICE
    // ====================================================
    Route::get('/prints/history', [PrintController::class, 'history'])->name('prints.history');
    Route::get('/prints/{id}/file', [PrintController::class, 'downloadFile'])->name('prints.file');
    Route::resource('prints', PrintController::class);
    Route::post('/printers/bulk-action', [PrinterController::class, 'bulkAction'])->name('printers.bulk_action');
    Route::resource('printers', PrinterController::class);

    // ====================================================
    // 8. MANAJEMEN USER (PEMINJAM)
    // ====================================================
    Route::post('peminjam-users/bulk-action', [PeminjamUserController::class, 'bulkAction'])->name('peminjam-users.bulk_action');
    Route::post('peminjam-users/{id}/reset-password', [PeminjamUserController::class, 'resetPassword'])->name('peminjam-users.reset-password');
    Route::resource('peminjam-users', PeminjamUserController::class);

    // ====================================================
    // 10. LOAN APPROVALS (ADMIN)
    // ====================================================
    Route::get('/admin/loans/pending', [App\Http\Controllers\AdminLoanController::class, 'indexPending'])->name('admin.loans.pending');
    Route::post('/admin/loans/{id}/approve', [App\Http\Controllers\AdminLoanController::class, 'approve'])->name('admin.loans.approve');
    Route::post('/admin/loans/{id}/reject', [App\Http\Controllers\AdminLoanController::class, 'reject'])->name('admin.loans.reject');

    // ====================================================
    // 9. SUPER ADMIN AREA (LOGS & USERS)
    // ====================================================

    // Item Helper: Smart Serial Generator
    Route::get('/items/next-sequence', [ItemController::class, 'getNextSequence'])->name('items.next_sequence');

    // A. LOG ACTIVITY (READ ONLY - Bisa diakses Admin Biasa)
    // Saya taruh di luar middleware superadmin agar admin biasa bisa lihat (kalau kebijakanmu begitu)
    Route::get('/superadmin/logs', [ActivityLogController::class, 'index'])->name('superadmin.logs.index');
    Route::get('/superadmin/logs/history/{model}/{id}', [ActivityLogController::class, 'history'])->name('superadmin.logs.history');

    // B. LOG ACTIVITY & USER MANAGEMENT (FULL ACCESS - Hanya Superadmin)
    Route::middleware(['superadmin']) // Pastikan middleware ini terdaftar di kernel
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {
            // CRUD Admin Users
            Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk_action');
            Route::resource('users', UserController::class);

            // Delete Logs Actions
            Route::delete('logs/clear-all', [ActivityLogController::class, 'destroyAll'])->name('logs.clear');
            Route::delete('logs/{id}', [ActivityLogController::class, 'destroy'])->name('logs.destroy');

            // Backup & Restore
            Route::get('/backup', [App\Http\Controllers\SuperAdmin\BackupController::class, 'index'])->name('backup.index');
            Route::post('/backup/download', [App\Http\Controllers\SuperAdmin\BackupController::class, 'download'])->name('backup.download');
            Route::post('/backup/database', [App\Http\Controllers\SuperAdmin\BackupController::class, 'backupDatabase'])->name('backup.database');
            Route::post('/backup/reset', [App\Http\Controllers\SuperAdmin\BackupController::class, 'resetDatabase'])->name('backup.reset');
            Route::post('/backup/import-items', [App\Http\Controllers\SuperAdmin\BackupController::class, 'importItems'])->name('backup.import_items');
        });

});

// Student Protected Routes (Needs Login)
Route::middleware(['auth:student'])->group(function () {
    Route::post('/student/loans/request', [App\Http\Controllers\LoanRequestController::class, 'store'])->name('student.loans.request');
    Route::get('/student/loans', [App\Http\Controllers\LoanRequestController::class, 'index'])->name('student.loans.index');
    Route::post('/student/password/update', [App\Http\Controllers\StudentAuthController::class, 'updatePassword'])->name('student.password.update');
});

require __DIR__ . '/auth.php';