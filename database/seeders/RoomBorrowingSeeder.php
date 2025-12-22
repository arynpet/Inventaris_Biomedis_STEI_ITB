<?php

namespace Database\Seeders;

use App\Models\RoomBorrowing;
use Illuminate\Database\Seeder;

class RoomBorrowingSeeder extends Seeder
{
    public function run(): void
    {
        RoomBorrowing::insert([
            [
                'room_id' => 3,
                'user_id' => 3,
                'start_time' => '2025-12-08 10:51:00',
                'end_time' => '2025-12-11 10:51:00',
                'purpose' => 'penelitian',
                'status' => 'pending',
                'notes' => 'minjem',
                'created_at' => '2025-12-07 20:51:46',
                'updated_at' => '2025-12-07 20:51:46',
            ],
        ]);
    }
}