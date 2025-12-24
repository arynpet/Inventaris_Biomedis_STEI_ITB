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
            'phone' => $this->faker->numerify('08##########'),
            'role' => $this->faker->randomElement(['mahasiswa', 'asisten', 'dosen']),
            'is_trained' => $this->faker->boolean(70),
        ];
    }

    public function trained(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_trained' => true,
        ]);
    }

    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'dosen',
            'is_trained' => true,
        ]);
    }
}