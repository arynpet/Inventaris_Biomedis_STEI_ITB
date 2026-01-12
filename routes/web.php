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

/*
|--------------------------------------------------------------------------
| Dashboard & Protected Routes (All Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 0. AI ASSISTANT (N.A.R.A)
    // ====================================================
    Route::prefix('nara')->name('nara.')->group(function () {
        Route::post('/chat', [NaraController::class, 'ask'])->name('chat');
        Route::post('/destroy', [NaraController::class, 'destroyAsset'])->name('destroy');
        Route::post('/store-batch', [NaraController::class, 'storeBatch'])->name('store_batch');
    });

    // ====================================================
    // 1. DASHBOARD
    // ====================================================
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ====================================================
    // 2. PROFILE MANAGEMENT
    // ====================================================
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ====================================================
    // 3. INVENTORY MANAGEMENT (ITEMS)
    // ====================================================
    // Trash & Restore
    Route::get('/items/trash', [ItemController::class, 'trash'])->name('items.trash');
    Route::get('/items/action/bulk-restore', [ItemController::class, 'bulkRestore'])->name('items.bulk_restore');
    Route::get('/items/{id}/restore', [ItemController::class, 'restore'])->name('items.restore');
    Route::delete('/items/{id}/terminate', [ItemController::class, 'terminate'])->name('items.terminate'); // Hard Delete

    // Bulk Actions & QR
    Route::post('items/regenerate-qr', [ItemController::class, 'regenerateAllQr'])->name('items.regenerate_qr');
    Route::post('items/bulk-action', [ItemController::class, 'bulkAction'])->name('items.bulk_action');
    Route::get('/items/{item}/qr-pdf', [ItemController::class, 'qrPdf'])->name('items.qr.pdf');
    Route::get('/api/items/by-qr/{qr}', [ItemController::class, 'findByQr']); // API internal

    // Barang Keluar (Logs)
    Route::prefix('items-management')->group(function () {
        Route::get('out-logs', [ItemController::class, 'outIndex'])->name('items.out.index');
        Route::get('out/{item}/create', [ItemController::class, 'outCreate'])->name('items.out.create');
        Route::post('out/{item}', [ItemController::class, 'outStore'])->name('items.out.store');
        Route::get('out/{item}/pdf', [ItemController::class, 'outPdf'])->name('items.out.pdf');
    });

    // Resource Items (Harus di bawah route custom items)
    Route::resource('items', ItemController::class);

    // ====================================================
    // 4. MASTER DATA
    // ====================================================
    // Rooms
    Route::post('rooms/bulk-action', [RoomController::class, 'bulkAction'])->name('rooms.bulk_action');
    Route::post('/rooms/move-item', [RoomController::class, 'moveItem'])->name('rooms.moveItem');
    Route::resource('rooms', RoomController::class);

    // Categories
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
    Route::resource('printers', PrinterController::class);

    // ====================================================
    // 8. MANAJEMEN USER (PEMINJAM)
    // ====================================================
    Route::resource('peminjam-users', PeminjamUserController::class);

    // ====================================================
    // 9. SUPER ADMIN AREA (LOGS & USERS)
    // ====================================================

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

require __DIR__ . '/auth.php';