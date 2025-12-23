<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemOut extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'recipient_name' => $this->faker->company(),
            'out_date' => now(),
            'reason' => 'Hibah / Transfer',
        ];
    }
}