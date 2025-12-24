<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word . ' Printer 3D',
            // Sesuaikan nama kolom kategori sesuai database (category atau material_type_id)
            // Berdasarkan PrinterTest Anda, sepertinya 'material_type_id' digunakan untuk menyimpan string kategori
            'material_type_id' => 'filament', 
            'status' => 'available',
        ];
    }
}