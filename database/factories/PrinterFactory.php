<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    public function definition(): array
    {
        $category = $this->faker->randomElement(['filament', 'resin']);
        
        return [
            'name' => $this->faker->company . ' Printer 3D',
            'category' => $category,
            'material_type_id' => $category, // Sesuai dengan kolom di tabel
            'status' => 'available',
            'description' => $this->faker->optional(0.5)->sentence(),
            'available_at' => null,
        ];
    }
}