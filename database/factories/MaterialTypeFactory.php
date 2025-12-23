<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(['filament', 'resin']),
            'name' => $this->faker->colorName() . ' Material',
            'stock_balance' => 1000,
            'unit' => 'gram',
        ];
    }
}