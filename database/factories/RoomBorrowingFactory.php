<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\PeminjamUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomBorrowingFactory extends Factory
{
    public function definition(): array
    {
        $start = now()->addDays(rand(1, 10));
        return [
            'room_id' => Room::factory(),
            'user_id' => PeminjamUser::factory(),
            'start_time' => $start,
            'end_time' => $start->copy()->addHours(2),
        ];
    }
}