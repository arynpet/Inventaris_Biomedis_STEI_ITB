<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;

class RemoteUploadController extends Controller
{
    /**
     * Generate QR for Return (URL-based for Web Mobile)
     */
    public function generateReturnQr()
    {
        $token = (string) Str::uuid();
        $url = route('mobile.upload.show', ['token' => $token]);

        $qrCode = QrCode::size(200)->generate($url);

        return response()->json([
            'token' => $token,
            'qr_code' => (string) $qrCode,
            'url' => $url
        ]);
    }

    /**
     * Show Mobile Upload Form
     */
    public function showMobileUploadForm($token)
    {
        return view('mobile.upload', compact('token'));
    }

    /**
     * Handle Mobile Upload (Web) with Processing
     */
    public function handleMobileUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB
            'token' => 'required|string',
        ]);

        try {
            $token = $request->input('token');
            $file = $request->file('image');

            // --- 1. SETUP IMAGE ---
            $image = Image::make($file);

            // --- 2. RESIZE (Max 1080px Width) ---
            $image->resize(1080, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Cegah gambar kecil dipaksa membesar
            });

            // --- 3. WATERMARK ---
            // Logic Nama Admin / Verifier
            // Karena ini public route, Auth::user() mungkin null.
            // Gunakan default 'System Verifier' seperti permintaan jika via QR.
            $adminName = auth()->check() ? auth()->user()->name : 'System Verifier';
            $timestamp = now()->format('d M Y H:i');
            $watermarkText = "$timestamp | Admin: $adminName";

            // Dimensi Gambar Baru
            $width = $image->width();
            $height = $image->height();

            // Buat Background Hitam Transparan di Bawah (Height 50px - 80px tergantung proporsi)
            $barHeight = 60;
            $image->rectangle(0, $height - $barHeight, $width, $height, function ($draw) {
                $draw->background('rgba(0, 0, 0, 0.5)');
            });

            // Tambahkan Teks Putih (Center Align)
            $image->text($watermarkText, $width / 2, $height - ($barHeight / 2), function ($font) use ($width) {
                // Coba gunakan font custom jika ada, atau default
                $fontPath = public_path('fonts/Inter-Bold.ttf');
                if (file_exists($fontPath)) {
                    $font->file($fontPath);
                } else {
                    // Fallback font number (1-5) for GD if file not found
                    $font->file(5);
                }

                $font->size($this->calculateFontSize($width)); // Dinamis base on width
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle'); // Middle dari bar
            });

            // --- 4. SAVE ---
            // Kita simpan ke folder temp dulu agar Logic Preview di PC (Polling) tetap jalan.
            // Nanti BorrowingController yang akan memindahkan file ini ke folder final (public/uploads/pengembalian).
            $filename = 'return_' . time() . '_' . Str::random(10) . '.jpg';
            $path = 'storage/temp/' . $filename;

            // Ensure directory exists
            if (!file_exists(public_path('storage/temp'))) {
                mkdir(public_path('storage/temp'), 0755, true);
            }

            // Save (Quality 80%)
            $image->save(public_path($path), 80);

            $publicUrl = asset($path);

            // Cache for Polling
            Cache::put('remote_upload_' . $token, $publicUrl, 600);

            return view('mobile.success');

        } catch (\Exception $e) {
            Log::error("Mobile Upload Error: " . $e->getMessage());
            return back()->with('error', 'Gagal memproses gambar: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk menghitung ukuran font proporsional
     */
    private function calculateFontSize($imgWidth)
    {
        // Misal lebar 1080 -> font 24
        // Lebar 500 -> font 12
        return max(14, round($imgWidth / 40));
    }

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