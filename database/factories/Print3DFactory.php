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
        $date = $this->faker->dateTimeBetween('+2 days', '+30 days');
        $startHour = $this->faker->numberBetween(8, 18);
        $duration = $this->faker->numberBetween(2, 8);
        
        return [
            'user_id' => PeminjamUser::factory()->trained(),
            'printer_id' => Printer::factory(),
            'date' => $date->format('Y-m-d'),
            'start_time' => sprintf('%02d:00', $startHour),
            'end_time' => sprintf('%02d:00', $startHour + $duration),
            'status' => $this->faker->randomElement(['pending', 'printing', 'done']),
            'material_type_id' => MaterialType::factory(),
            'material_amount' => $this->faker->numberBetween(10, 500),
            'material_unit' => 'gram',
            'material_source' => $this->faker->randomElement(['lab', 'penelitian', 'dosen', 'pribadi']),
            'material_deducted' => false,
            'notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }
}