<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::insert([
            [
                'name' => 'LAB BIOMEDIS',
                'status' => 'dipinjam',
                'code' => '001',
                'description' => 'Lab Biomedis',
                'created_at' => '2025-12-04 17:58:24',
                'updated_at' => '2025-12-07 09:39:29',
            ],
            [
                'name' => 'RUANG LAINNYA',
                'status' => 'sedia',
                'code' => '003',
                'description' => 'Ruang Lainnya',
                'created_at' => '2025-12-07 09:39:24',
                'updated_at' => '2025-12-07 09:39:24',
            ],
            [
                'name' => 'RUANG RESIDENSI',
                'status' => 'dipinjam',
                'code' => '002',
                'description' => 'Ruang Residensi',
                'created_at' => '2025-12-07 09:39:24',
                'updated_at' => '2025-12-07 21:04:31',
            ],
        ]);
    }
}