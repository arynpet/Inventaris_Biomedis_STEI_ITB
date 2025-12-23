<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\PeminjamUser;
use App\Models\MaterialType;
use Illuminate\Database\Eloquent\Factories\Factory;

class Print3DFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => PeminjamUser::factory(),
            'printer_id' => Printer::factory(), // Pastikan PrinterFactory juga ada
            'date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'status' => 'pending',
            'material_type_id' => MaterialType::factory(),
            'material_amount' => 50,
            'material_unit' => 'gram',
            'material_source' => 'lab',
            'material_deducted' => false,
            'notes' => 'Factory generated print job',
        ];
    }
}