<?php
namespace App\Services\Nara;

use App\Models\Room;

class RoomTool
{
    public function search($filters)
    {
        $query = Room::query();
        // Cari berdasarkan nama atau kode
        if (!empty($filters['keyword'])) {
            $query->where('name', 'LIKE', '%' . $filters['keyword'] . '%')
                  ->orWhere('code', 'LIKE', '%' . $filters['keyword'] . '%');
        }
        return $query->get();
    }

    public function prepareCreatePreview($rawRooms)
    {
        foreach ($rawRooms as &$room) {
            // Pastikan kolom wajib terisi
            $room['code'] = $room['code'] ?? strtoupper(substr($room['name'] ?? 'RM', 0, 3)) . '-' . rand(100,999);
            $room['status'] = 'active';
            $room['description'] = $room['description'] ?? '-';
        }
        return $rawRooms;
    }
}