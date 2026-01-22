<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScanController;

/*
|--------------------------------------------------------------------------
| API Routes (SECURED)
|--------------------------------------------------------------------------
|
| All sensitive endpoints are protected with auth:sanctum middleware.
| Public endpoints have rate limiting to prevent abuse.
|
*/

// ====================================================
// PUBLIC ROUTES (with Rate Limiting)
// ====================================================

// Authentication - Public but throttled
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // Max 5 attempts per minute

// ====================================================
// PROTECTED ROUTES (Requires Sanctum Token)
// ====================================================

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Scanner - Get Item by Serial Number (MOVED TO PROTECTED)
    // This prevents unauthorized users from scanning the entire inventory
    Route::get('/scan/{serial_number}', [ScanController::class, 'showBySerial']);

    // Test/Debug Route - Get authenticated user info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    });
});

// ====================================================
// REMOTE UPLOAD (Public/Mobile)
// ====================================================
Route::post('/remote-upload', [App\Http\Controllers\RemoteUploadController::class, 'uploadFromMobile']);
Route::get('/remote-check/{token}', [App\Http\Controllers\RemoteUploadController::class, 'checkStatus']);

// ====================================================
// FALLBACK: Unauthorized Access
// ====================================================

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint not found or unauthorized. Please authenticate first.',
    ], 404);
});
