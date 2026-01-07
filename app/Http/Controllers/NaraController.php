<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Item; 
use App\Models\Room; // Pastikan Model Room di-import

class NaraController extends Controller
{
    /**
     * Logic Utama NARA (Otak AI)
     */
    public function ask(Request $request)
    {
        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) return response()->json(['reply' => 'Error: API Key Missing'], 500);

        // Inject Tahun Sekarang ke Prompt agar AI selalu update (misal: 25)
        $currentYearShort = date('y'); 
        $currentYearFull = date('Y');

        // 1. SKEMA & KONTEKS
        $dbSchema = "
        Tabel 'items':
        - asset_number (String. Kode Aset. Bisa sama untuk banyak barang dalam 1 batch pengadaan)
        - name (String. Nama Barang. Jika >1, harus unik: 'Mouse 1', 'Mouse 2')
        - room_id (Integer. ID Ruangan)
        - serial_number (String. Unik. Format: [KAT]-[SUB]-[THN]-[URUT])
        - status (available, borrowed, maintenance)
        - condition (good, damaged, broken)
        - quantity (Integer. Default 1 per baris/item)
        - source (String. Default: 'Pengadaan')
        - acquisition_year (Integer. Default: $currentYearFull)
        - placed_in_service_at (Date. Default: Hari ini)
        - fiscal_group (String. Default: 'Inventaris Kantor')
        ";

        $systemInstruction = "
            Role: Kamu adalah NARA (Neural Asset Assistant).
            Konteks: Inventory Lab Biomedis.
            
            ATURAN UTAMA:
            1. Output WAJIB JSON.
            2. Tahun untuk Serial Number (SN) WAJIB menggunakan: '$currentYearShort' (Tahun ini).
            3. Jika user meminta membuat banyak barang (misal 5 Mouse):
               - Asset Number: GUNAKAN 1 KODE YANG SAMA untuk kelimanya.
               - Nama: Beri nomor urut di nama (contoh: 'Mouse 1', 'Mouse 2', ... 'Mouse 5').
               - Serial Number: Urutkan digit belakangnya (contoh: ...-{$currentYearShort}001, ...-{$currentYearShort}002).
            4. Kategori SN (KAT): E (Elektronik), G (Glassware), M (Furniture).
            
            LOGIKA ACTION:
            
            [KASUS 1: REQUEST PEMBUATAN BARANG]
            User: 'Buatkan 3 Mikroskop di Lab Bio dengan aset INV-001'
            Action: 'CREATE_PREVIEW'
            Reply: 'Siap. Berikut draft data untuk 3 Mikroskop. Silakan konfirmasi untuk menyimpan.'
            Items: [
               { 
                 'asset_number': 'INV-001', 'name': 'Mikroskop 1', 'serial_number': 'E-MIC-{$currentYearShort}001', 
                 'room_name': 'Lab Bio', 'status': 'available', 'condition': 'good', ...default lain 
               },
               { 
                 'asset_number': 'INV-001', 'name': 'Mikroskop 2', 'serial_number': 'E-MIC-{$currentYearShort}002', 
                 'room_name': 'Lab Bio', ... 
               },
               ...
            ]

            [KASUS 2: HAPUS BARANG]
            User: 'Hapus barang SN-123' atau 'Hapus semua Mouse rusak'
            Action: 'DELETE_CONFIRMATION'
            Delete Info: { 'type': 'query', 'filter': { 'identifier': '...', 'keyword': '...', 'condition': '...' } }

            [KASUS 3: PENCARIAN]
            Action: 'SEARCH'
            Filters: { ... }

            [KASUS 4: CHAT]
            Action: 'CHAT'

            FORMAT JSON OUTPUT:
            {
                'action': 'CREATE_PREVIEW' | 'DELETE_CONFIRMATION' | 'SEARCH' | 'CHAT',
                'reply': '...',
                'items_preview': [ ... ] (Hanya untuk CREATE_PREVIEW),
                'delete_info': { ... } (Hanya untuk DELETE_CONFIRMATION),
                'filters': { ... } (Hanya untuk SEARCH)
            }
        ";

        // 2. HISTORY
        $history = Session::get('nara_history', []);
        $payloadContents = [];
        $payloadContents[] = ['role' => 'user', 'parts' => [['text' => $systemInstruction]]];
        foreach ($history as $chat) {
            $payloadContents[] = ['role' => 'user', 'parts' => [['text' => $chat['user']]]];
            $payloadContents[] = ['role' => 'model', 'parts' => [['text' => $chat['ai']]]]; 
        }
        $payloadContents[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        try {
            // 3. CALL GEMINI API
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", ['contents' => $payloadContents]);
            
            $data = $response->json();
            if (isset($data['error'])) return response()->json(['reply' => 'AI Error: ' . $data['error']['message']]);

            // 4. PARSE JSON
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $aiResponse = null;
            if (preg_match('/\{.*\}/s', $rawText, $matches)) {
                $aiResponse = json_decode($matches[0], true);
            } else {
                $aiResponse = ['action' => 'CHAT', 'reply' => strip_tags($rawText)];
            }

            // 5. EKSEKUSI LOGIC
            $responseData = [
                'action' => $aiResponse['action'] ?? 'CHAT',
                'reply' => $aiResponse['reply'] ?? 'Processing...',
                'data' => [],
                'target_items' => [],
                'items_preview' => [] 
            ];

            // >>> LOGIC A: CREATE PREVIEW (Konfirmasi Pembuatan)
            if ($responseData['action'] === 'CREATE_PREVIEW') {
                $rawItems = $aiResponse['items_preview'] ?? [];
                
                // Proses setiap item untuk melengkapi data (misal cari room_id)
                foreach ($rawItems as &$item) {
                    // 1. Cari Room ID berdasarkan nama ruangan yang diberi AI
                    if (isset($item['room_name'])) {
                        $room = Room::where('name', 'LIKE', '%' . $item['room_name'] . '%')->first();
                        $item['room_id'] = $room ? $room->id : 1; // Default ID 1 jika tidak ketemu
                        // Simpan nama ruangan asli untuk display di tabel konfirmasi
                        $item['display_room'] = $room ? $room->name : 'Gudang Utama (Default)';
                        unset($item['room_name']);
                    } else {
                        $item['room_id'] = 1;
                        $item['display_room'] = 'Gudang Utama';
                    }

                    // 2. Pastikan Default Values Terisi (Safety Net)
                    $item['quantity'] = 1; // Kita paksa 1 karena AI sudah memecah rownya (Mouse 1, Mouse 2)
                    $item['source'] = $item['source'] ?? 'Pengadaan';
                    $item['acquisition_year'] = $item['acquisition_year'] ?? date('Y');
                    $item['placed_in_service_at'] = $item['placed_in_service_at'] ?? date('Y-m-d');
                    $item['fiscal_group'] = $item['fiscal_group'] ?? 'Aset Tetap';
                }
                
                $responseData['items_preview'] = $rawItems;
            }

            // >>> LOGIC B: SEARCH
            if ($responseData['action'] === 'SEARCH') {
                $query = Item::with('room');
                $f = $aiResponse['filters'] ?? [];

                if (!empty($f['identifier'])) {
                    $query->where(function($q) use ($f) {
                        $q->where('serial_number', $f['identifier'])
                          ->orWhere('asset_number', $f['identifier'])
                          ->orWhere('qr_code', $f['identifier']);
                    });
                } elseif (!empty($f['keyword'])) {
                    $query->where('name', 'LIKE', '%'.$f['keyword'].'%');
                }

                if (!empty($f['room'])) $query->whereHas('room', fn($q) => $q->where('name', 'LIKE', '%'.$f['room'].'%'));
                if (!empty($f['status'])) $query->where('status', $f['status']);
                if (!empty($f['condition'])) $query->where('condition', $f['condition']);
                
                $responseData['data'] = $query->limit(20)->get();
                if ($responseData['data']->isEmpty()) $responseData['reply'] .= " (Data tidak ditemukan).";
            }

            // >>> LOGIC C: DELETE CONFIRMATION
            if ($responseData['action'] === 'DELETE_CONFIRMATION') {
                $delInfo = $aiResponse['delete_info'] ?? [];
                $query = Item::query();
                $filterApplied = false;
                $filters = $delInfo['filter'] ?? [];

                if (!empty($filters['identifier'])) {
                    $id = $filters['identifier'];
                    $query->where(function($q) use ($id) {
                        $q->where('serial_number', $id)->orWhere('asset_number', $id);
                    });
                    $filterApplied = true;
                } elseif (!empty($filters['keyword'])) {
                    $query->where('name', 'LIKE', '%' . $filters['keyword'] . '%');
                    $filterApplied = true;
                }

                if (!empty($filters['room'])) {
                    $query->whereHas('room', fn($q) => $q->where('name', 'LIKE', '%' . $filters['room'] . '%'));
                    $filterApplied = true;
                }
                if (!empty($filters['condition'])) {
                    $query->where('condition', $filters['condition']);
                    $filterApplied = true;
                }

                if ($filterApplied) {
                    $responseData['target_items'] = $query->select('serial_number', 'name', 'asset_number', 'room_id')->get();
                }

                if (empty($responseData['target_items']) || $responseData['target_items']->isEmpty()) {
                    $responseData['action'] = 'CHAT';
                    $responseData['reply'] = "Saya tidak menemukan barang yang cocok untuk dihapus.";
                } else {
                    $count = $responseData['target_items']->count();
                    $names = $responseData['target_items']->pluck('name')->unique()->take(3)->implode(', ');
                    if($count > 3) $names .= " dan lainnya";
                    $responseData['reply'] = "⚠️ KONFIRMASI: Ditemukan **$count item** ($names). Yakin hapus?";
                }
            }

            // 6. SAVE HISTORY
            $history[] = ['user' => $userMessage, 'ai' => $rawText];
            if (count($history) > 10) array_shift($history);
            Session::put('nara_history', $history);

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'System Failure: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 1. FUNGSI HAPUS (Batch)
     */
    public function destroyAsset(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'serial_numbers' => 'required|array|max:50',  // Max 50 items per batch
            'serial_numbers.*' => 'required|string|max:100',
        ]);

        $serials = $validated['serial_numbers'];
        $deleted = Item::whereIn('serial_number', $serials)->delete();
        
        if ($deleted > 0) {
            return response()->json([
                'success' => true, 
                'message' => "$deleted aset berhasil dihapus."
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Tidak ada item yang ditemukan untuk dihapus.'
        ], 404);
    }

    /**
     * 2. FUNGSI CREATE (Batch) - BARU
     * Dipanggil setelah user klik "CONFIRM" di frontend
     */
/**
     * 2. FUNGSI CREATE (Batch) - REVISED (SMART INCREMENT)
     * Menangani duplikasi serial number secara otomatis agar semua barang tersimpan.
     */
    public function storeBatch(Request $request)
    {
        // Comprehensive validation
        $validated = $request->validate([
            'items' => 'required|array|max:50|min:1',  // Limit to 50 items per batch
            'items.*.name' => 'required|string|max:255',
            'items.*.serial_number' => 'required|string|max:100',
            'items.*.asset_number' => 'nullable|string|max:100',
            'items.*.room_id' => 'required|integer|exists:rooms,id',
            'items.*.quantity' => 'nullable|integer|min:1|max:1000',
            'items.*.source' => 'nullable|string|max:100',
            'items.*.acquisition_year' => 'nullable|integer|min:1900|max:2100',
            'items.*.placed_in_service_at' => 'nullable|date',
            'items.*.fiscal_group' => 'nullable|string|max:100',
            'items.*.status' => 'nullable|in:available,borrowed,maintenance',
            'items.*.condition' => 'nullable|in:good,damaged,broken',
        ]);

        $items = $validated['items'];

        $count = 0;
        
        foreach ($items as $itemData) {
            // 1. Bersihkan data
            unset($itemData['display_room']); 
            
            // 2. LOGIKA ANTI-BENTROK SERIAL NUMBER
            // Kita cek loop: Jika SN sudah ada, kita naikkan angkanya (Increment)
            // Contoh: E-MOU-25001 (ada) -> ubah jadi E-MOU-25002 -> dst.
            
            $originalSn = $itemData['serial_number'];
            $loopGuard = 0; // Penjaga agar tidak infinite loop

            while (Item::where('serial_number', $itemData['serial_number'])->exists()) {
                // Ambil angka paling belakang dari string
                if (preg_match('/(\d+)$/', $itemData['serial_number'], $matches)) {
                    $number = $matches[1]; // misal "001"
                    $length = strlen($number); // panjang 3 digit
                    $newNumber = $number + 1; // jadi 2
                    
                    // Format ulang jadi "002" (pertahankan leading zero)
                    $paddedNumber = str_pad($newNumber, $length, '0', STR_PAD_LEFT);
                    
                    // Ganti angka lama dengan angka baru di string SN
                    $itemData['serial_number'] = preg_replace('/'.$number.'$/', $paddedNumber, $itemData['serial_number']);
                } else {
                    // Fallback jika format SN aneh/tidak ada angka di belakang
                    // Tambahkan suffix random agar tetap unik
                    $itemData['serial_number'] = $originalSn . '-' . rand(100, 999);
                }

                $loopGuard++;
                if ($loopGuard > 100) break; // Berhenti jika sudah mencoba 100x (safety)
            }

            // 3. Simpan ke Database
            try {
                Item::create($itemData);
                $count++;
            } catch (\Exception $e) {
                // Skip jika masih error (jarang terjadi dengan logika di atas)
                continue;
            }
        }

        if ($count > 0) {
            return response()->json([
                'success' => true, 
                // Kirim pesan detail berapa yang diminta vs berhasil
                'message' => "Berhasil menyimpan $count dari " . count($items) . " aset baru."
            ]);
        }
        
        return response()->json(['success' => false, 'message' => "Gagal menyimpan data."], 400);
    }
}