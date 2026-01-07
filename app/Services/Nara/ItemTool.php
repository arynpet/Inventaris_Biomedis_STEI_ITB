<?php
namespace App\Services\Nara;

use App\Models\Item;
use App\Models\Room;

class ItemTool
{
    public function search($filters)
    {
        $query = Item::with('room');

        // 1. FILTER ROOM (Prioritas Utama)
        // Jika param 'room' ada isinya, cari berdasarkan nama ruangan
        if (!empty($filters['room'])) {
            $query->whereHas('room', function($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['room'] . '%');
            });
        }

        // 2. FILTER KEYWORD (Nama Barang)
        if (!empty($filters['keyword'])) {
            $query->where('name', 'LIKE', '%' . $filters['keyword'] . '%');
        }

        // 3. Filter Kode Spesifik
        if (!empty($filters['identifier'])) {
            $query->where('serial_number', $filters['identifier'])
                  ->orWhere('asset_number', $filters['identifier']);
        }

        if (!empty($filters['status'])) $query->where('status', $filters['status']);

        $data = $query->limit(20)->get();
        return $data->isEmpty() ? null : $data;
    }

    public function prepareCreatePreview($rawItems)
    {
        foreach ($rawItems as &$item) {
            // Logic Cari Room ID
            if (isset($item['room_name'])) {
                $room = Room::where('name', 'LIKE', '%' . $item['room_name'] . '%')->first();
                $item['room_id'] = $room ? $room->id : 1; 
                $item['display_room'] = $room ? $room->name : 'Gudang Utama';
                unset($item['room_name']);
            } else {
                $item['room_id'] = 1;
                $item['display_room'] = 'Gudang Utama';
            }
            // Defaults
            $item['quantity'] = 1;
            $item['source'] = 'Pengadaan AI';
            $item['acquisition_year'] = date('Y');
            $item['fiscal_group'] = 'Aset Tetap';
        }
        return $rawItems;
    }

    // Logic Simpan Satu Item (Smart Increment)
    public function storeSingle($data)
    {
        // Loop cek SN duplikat
        $loop = 0;
        while (Item::where('serial_number', $data['serial_number'])->exists() && $loop < 50) {
            if (preg_match('/(\d+)$/', $data['serial_number'], $m)) {
                $num = $m[1];
                $newNum = str_pad($num + 1, strlen($num), '0', STR_PAD_LEFT);
                $data['serial_number'] = preg_replace('/'.$num.'$/', $newNum, $data['serial_number']);
            } else {
                $data['serial_number'] .= '-' . rand(1,9);
            }
            $loop++;
        }
        return Item::create($data);
    }

    public function getDeleteCandidates($filters) { /* ... Logic Delete yg lama ... */ return collect([]); }
}