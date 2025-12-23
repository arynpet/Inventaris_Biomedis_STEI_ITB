<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\PeminjamUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => PeminjamUser::factory(),
            'borrow_date' => now(),
            'return_date' => now()->addDays(3),
            'status' => 'borrowed',
            'notes' => $this->faker->sentence(),
            'return_condition' => null,
        ];
    }
}