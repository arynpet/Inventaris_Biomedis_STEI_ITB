<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialTypeFactory extends Factory
{
    public function definition(): array
    {
        $category = $this->faker->randomElement(['filament', 'resin']);
        
        if ($category === 'filament') {
            $name = $this->faker->randomElement(['PLA', 'ABS', 'PETG', 'TPU']) . ' ' . 
                    $this->faker->randomElement(['Black', 'White', 'Red', 'Blue']);
            $unit = 'gram';
        } else {
            $name = $this->faker->randomElement(['Standard', 'Tough', 'Flexible', 'Clear']);
            $unit = 'mililiter';
        }
        
        return [
            'category' => $category,
            'name' => $name,
            'stock_balance' => $this->faker->numberBetween(100, 5000), // Stock awal yang cukup
            'unit' => $unit,
        ];
    }
}