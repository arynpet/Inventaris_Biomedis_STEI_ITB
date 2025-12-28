<?php

use Illuminate\Support\Facades\Route;
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Dashboard & Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 1. Profile
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | 2. Inventory Management (Items)
    |--------------------------------------------------------------------------
    | PENTING: Route spesifik harus ditaruh SEBELUM Route::resource('items')
    */

/*
    |--------------------------------------------------------------------------
    | Inventory Module Routes (Items)
    |--------------------------------------------------------------------------
    | PENTING: Urutan di bawah ini SANGAT KRUSIAL.
    | Jangan ubah urutannya agar tidak 404 atau tertabrak Route::resource.
    */

    // 1. Halaman View Sampah
    Route::get('/items/trash', [ItemController::class, 'trash'])
        ->name('items.trash');

    // 2. Aksi Bulk Restore (GET - Agar support Link "Urungkan" & URL ?ids=1,2,3)
    // Saya persingkat URL-nya agar netral (bisa dari index atau trash)
    Route::get('/items/action/bulk-restore', [ItemController::class, 'bulkRestore'])
        ->name('items.bulk_restore');

    // 3. Aksi Single Restore (GET - Agar support Link "Urungkan")
    Route::get('/items/{id}/restore', [ItemController::class, 'restore'])
        ->name('items.restore');

    // 4. Aksi Terminate / Hapus Permanen (DELETE - Wajib form/post demi keamanan)
    Route::delete('/items/{id}/terminate', [ItemController::class, 'terminate'])
        ->name('items.terminate');

    // 5. Fitur Tambahan (QR, Log, dll - Taruh sebelum Resource)
    Route::post('items/regenerate-qr', [ItemController::class, 'regenerateAllQr'])->name('items.regenerate_qr');
    Route::post('items/bulk-action', [ItemController::class, 'bulkAction'])->name('items.bulk_action');
    
    // Group Barang Keluar
    Route::prefix('items-management')->group(function () {
        Route::get('out-logs', [ItemController::class, 'outIndex'])->name('items.out.index');
        Route::get('out/{item}/create', [ItemController::class, 'outCreate'])->name('items.out.create');
        Route::post('out/{item}', [ItemController::class, 'outStore'])->name('items.out.store');
        Route::get('out/{item}/pdf', [ItemController::class, 'outPdf'])->name('items.out.pdf');
    });

    Route::get('/items/{item}/qr-pdf', [ItemController::class, 'qrPdf'])->name('items.qr.pdf');
    Route::get('/api/items/by-qr/{qr}', [ItemController::class, 'findByQr']);

    // 6. ROUTE UTAMA (RESOURCE)
    // Ini WAJIB ditaruh paling bawah di blok items agar tidak memakan route custom di atasnya
    Route::resource('items', ItemController::class);


    /*
    |--------------------------------------------------------------------------
    | 3. Inventory Master Data (Rooms, Categories, Materials)
    |--------------------------------------------------------------------------
    */

    // Di dalam routes/web.php

    // Route Bulk Action Ruangan (Taruh SEBELUM resource rooms)
    Route::post('rooms/bulk-action', [App\Http\Controllers\RoomController::class, 'bulkAction'])->name('rooms.bulk_action');

    // Route Resource Standar
    Route::resource('rooms', App\Http\Controllers\RoomController::class);
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/move-item', [RoomController::class, 'moveItem'])->name('rooms.moveItem'); // Move Item Logic
    
    Route::resource('categories', CategoryController::class);
    
    // Material & Stock
        // 1. Route untuk Add Stock (POST)
    Route::post('/materials/{id}/add-stock', [MaterialTypeController::class, 'addStock'])->name('materials.add_stock');

    // 2. Route untuk Bulk Action (Hapus Banyak)
    Route::post('/materials/bulk-action', [MaterialTypeController::class, 'bulkAction'])->name('materials.bulk_action');
    Route::post('/materials/{material}/add-stock', [MaterialTypeController::class, 'addStock'])->name('materials.addStock');
    Route::resource('materials', MaterialTypeController::class);


    /*
    |--------------------------------------------------------------------------
    | 4. Borrowing System (Peminjaman)
    |--------------------------------------------------------------------------
    | PENTING: Route history/pdf/return ditaruh SEBELUM resource('borrowings')

    
    */
    // History & Reporting

    // Route Bulk Return (Pengembalian Masal)
    Route::post('/borrowings/bulk-return', [BorrowingController::class, 'bulkReturn'])->name('borrowings.bulk_return');

    Route::get('/borrowings/history', [BorrowingController::class, 'history'])->name('borrowings.history');
    Route::get('/borrowings/history/pdf', [BorrowingController::class, 'historyPdf'])->name('borrowings.historyPdf');
    
    // Actions
    Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');
    Route::post('/borrowings/scan-qr', [BorrowingController::class, 'findItemByQr'])->name('borrowings.scan');
    Route::get('/borrowings/{id}/pdf', [BorrowingController::class, 'pdf'])->name('borrowings.pdf');



    // ...

    // 1. Approve (Baru)
Route::put('/room_borrowings/{id}/approve', [RoomBorrowingController::class, 'approveRoom'])->name('room_borrowings.approve');

// 2. Return/Selesai (Yang tadi)
Route::put('/room_borrowings/{id}/return', [RoomBorrowingController::class, 'returnRoom'])->name('room_borrowings.return');

    // 1. Route History (GET)
    Route::get('/room_borrowings/history', [RoomBorrowingController::class, 'history'])->name('room_borrowings.history');

    // 2. Route Kembalikan Ruangan (PUT)
    Route::put('/room_borrowings/{id}/return', [RoomBorrowingController::class, 'returnRoom'])->name('room_borrowings.return');

    // 3. Resource Route (Yang sudah ada)
    Route::resource('room_borrowings', RoomBorrowingController::class);

    // Resources
    Route::resource('borrowings', BorrowingController::class);
    Route::resource('room_borrowings', RoomBorrowingController::class); // Peminjaman Ruangan


    /*
    |--------------------------------------------------------------------------
    | 5. Users & Printing
    |--------------------------------------------------------------------------
    */
    Route::resource('peminjam-users', PeminjamUserController::class);



// ... inside middleware group ...

// Route History
Route::get('/prints/history', [PrintController::class, 'history'])->name('prints.history');


// Resource Route
Route::resource('prints', PrintController::class);
    
    Route::resource('printers', PrinterController::class);
    Route::get('/prints/{id}/file', [PrintController::class, 'downloadFile'])->name('prints.file');
    Route::resource('prints', PrintController::class);

});

require __DIR__ . '/auth.php';