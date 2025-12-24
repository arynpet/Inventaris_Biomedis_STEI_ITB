<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??-###')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'status' => 'sedia', // Default status (WAJIB ADA)
        ];
    }
}