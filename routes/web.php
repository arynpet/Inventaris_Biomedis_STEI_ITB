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
| Dashboard (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | Inventory Module Routes (Rooms, Items, Categories)
    |--------------------------------------------------------------------------
    */


    Route::resource('rooms', RoomController::class);
Route::resource('items', ItemController::class);

// Route Tambahan untuk Fitur Barang Keluar
Route::prefix('items-management')->group(function () {
    // Halaman daftar riwayat barang keluar
    Route::get('out-logs', [ItemController::class, 'outIndex'])->name('items.out.index');
    
    // Halaman form proses pengeluaran barang
    Route::get('out/{item}/create', [ItemController::class, 'outCreate'])->name('items.out.create');
    
    // Proses simpan data pengeluaran
    Route::post('out/{item}', [ItemController::class, 'outStore'])->name('items.out.store');
    
    // Download PDF Surat Pengeluaran
    Route::get('out/{item}/pdf', [ItemController::class, 'outPdf'])->name('items.out.pdf');
});
    Route::resource('categories', CategoryController::class);
    Route::resource('peminjam-users', PeminjamUserController::class);
    Route::resource('materials', MaterialTypeController::class);
    Route::resource('prints', PrintController::class);
    Route::resource('printers', PrinterController::class);
    Route::get('/prints/{id}/file', [PrintController::class, 'downloadFile'])->name('prints.file');

    Route::get('/items/{item}/qr-pdf', [ItemController::class, 'qrPdf'])
    ->name('items.qr.pdf');

    Route::get('/borrowings/history', [BorrowingController::class, 'history'])
        ->name('borrowings.history');
    Route::post('/borrowings/{id}/return', [BorrowingController::class, 'return'])
        ->name('borrowings.return');
    Route::resource('borrowings', BorrowingController::class);

    Route::resource('room_borrowings', RoomBorrowingController::class);

    // Route khusus pemindahan barang
    Route::post('/rooms/move-item', [RoomController::class, 'moveItem'])
        ->name('rooms.moveItem');

    // Export whole history (optionally accept ?from=YYYY-MM-DD&to=YYYY-MM-DD)
    Route::get('/borrowings/history/pdf', [BorrowingController::class, 'historyPdf'])
        ->name('borrowings.history.pdf');

    // Export single borrowing detail
    Route::get('/borrowings/{id}/pdf', [BorrowingController::class, 'pdf'])
        ->name('borrowings.pdf');

    Route::get('/borrowings/history/pdf', [BorrowingController::class, 'historyPdf'])
    ->name('borrowings.historyPdf');

    Route::post('/borrowings/scan-qr', [BorrowingController::class, 'findItemByQr'])
     ->name('borrowings.scan');

     Route::get('/api/items/by-qr/{qr}', [ItemController::class, 'findByQr']);

     Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');


});

require __DIR__ . '/auth.php';

