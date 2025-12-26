<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\PeminjamUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowingFactory extends Factory
{
    public function definition(): array
    {
        $borrowDate = $this->faker->dateTimeBetween('-30 days', 'now');
        $hasReturned = $this->faker->boolean(60);

        return [
            'item_id' => Item::factory()->available(), // Pastikan item available
            'user_id' => PeminjamUser::factory()->trained(),
            'borrow_date' => $borrowDate,
            'return_date' => $hasReturned 
                ? $this->faker->dateTimeBetween($borrowDate, 'now')
                : null,
            'status' => $hasReturned ? 'returned' : 'borrowed',
            'return_condition' => $hasReturned 
                ? $this->faker->randomElement(['good', 'damaged', 'broken'])
                : null,
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function returned(): static
    {
        $borrowDate = $this->faker->dateTimeBetween('-30 days', '-1 days');
        
        return $this->state(fn (array $attributes) => [
            'borrow_date' => $borrowDate,
            'return_date' => $this->faker->dateTimeBetween($borrowDate, 'now'),
            'status' => 'returned',
            'return_condition' => $this->faker->randomElement(['good', 'damaged']),
        ]);
    }
    
    public function configure(): static
    {
        return $this->afterCreating(function ($borrowing) {
            // Update status item jika borrowed
            if ($borrowing->status === 'borrowed') {
                $borrowing->item->update(['status' => 'borrowed']);
            }
        });
    }
}