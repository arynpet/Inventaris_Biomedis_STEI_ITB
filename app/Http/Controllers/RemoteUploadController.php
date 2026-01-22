<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RemoteUploadController extends Controller
{
    /**
     * 1. Generate Token (Dipanggil oleh Laptop saat klik tombol)
     * Route: GET /remote-upload/token (Web)
     */
    public function generateToken()
    {
        $token = (string) Str::uuid();

        // Data JSON yang akan dibaca oleh Flutter
        $qrData = json_encode([
            'action' => 'upload',
            'token' => $token,
        ]);

        // Generate QR Code (SVG Format)
        // Ukuran 200px sudah cukup jelas untuk discan HP
        $qrCode = QrCode::size(200)->generate($qrData);

        return response()->json([
            'token' => $token,
            'qr_code' => (string) $qrCode
        ]);
    }

    /**
     * 2. Upload from Mobile (Dipanggil oleh HP/Flutter)
     * Route: POST /api/remote-upload (API)
     */
    public function uploadFromMobile(Request $request)
    {
        // ... Validasi & Log sama seperti sebelumnya ...
        Log::info('📥 Remote Upload Request Masuk', ['ip' => $request->ip()]);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'image' => 'required|image|max:20480',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error'], 422);
        }

        try {
            $token = $request->input('token');
            $file = $request->file('image');

            // Simpan File
            $filename = 'remote_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            // $path isinya: "temp/remote_blabla.jpg"
            $path = $file->storeAs('temp', $filename, 'public');

            // --- PERBAIKAN DI SINI ---
            // Gunakan helper asset() untuk memaksa URL menjadi http://...
            $publicUrl = asset('storage/' . $path);

            // Simpan ke Cache
            Cache::put('remote_upload_' . $token, $publicUrl, 600);

            Log::info("✅ Remote upload success. URL: {$publicUrl}");

            return response()->json([
                'status' => 'success',
                'message' => 'Image uploaded successfully',
                'url' => $publicUrl
            ]);

        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * 3. Check Status (Polling oleh Laptop)
     * Route: GET /api/remote-check/{token} (API)
     */
    public function checkStatus($token)
    {
        $cacheKey = 'remote_upload_' . $token;

        if (Cache::has($cacheKey)) {
            $url = Cache::get($cacheKey);

            // Hapus cache agar token tidak bisa dipakai ulang (One-time use)
            Cache::forget($cacheKey);

            return response()->json([
                'status' => 'found',
                'url' => $url
            ]);
        }

        return response()->json([
            'status' => 'waiting'
        ]);
    }
}