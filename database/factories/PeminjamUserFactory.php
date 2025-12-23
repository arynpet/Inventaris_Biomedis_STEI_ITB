<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'nim' => $this->faker->unique()->numerify('135#####'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'role' => 'mahasiswa',
            'is_trained' => true, // Default true agar bisa tes print/pinjam
        ];
    }
}