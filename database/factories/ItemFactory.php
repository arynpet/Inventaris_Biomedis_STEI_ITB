<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word . ' ' . $this->faker->randomNumber(3),
            'asset_number' => 'AST-' . $this->faker->unique()->numerify('#####'),
            'serial_number' => 'SN-' . $this->faker->unique()->numerify('#####'),
            'room_id' => Room::factory(), // Otomatis buat room jika tidak diisi
            'quantity' => $this->faker->numberBetween(1, 10),
            'source' => $this->faker->company(),
            'acquisition_year' => $this->faker->year(),
            'placed_in_service_at' => $this->faker->date(),
            'fiscal_group' => 'Group A',
            'status' => 'available', // Default aman untuk tes
            'condition' => 'good',   // Default aman untuk tes (WAJIB ADA)
        ];
    }
}