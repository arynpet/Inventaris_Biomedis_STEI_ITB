<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Mikroskop Digital', 'Jangka Sorong', 'Multimeter', 'Oscilloscope',
                'Function Generator', 'Power Supply', 'Meja Lab', 'Kursi Lab',
                'Rak Penyimpanan', 'Printer 3D', 'Soldering Station', 'PCB Drill',
            ]) . ' ' . $this->faker->numberBetween(1, 100),
            'asset_number' => $this->faker->unique()->numerify('AST-####'),
            'serial_number' => 'SN-' . $this->faker->unique()->numerify('#####'),
            'room_id' => Room::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'source' => $this->faker->randomElement(['STEI', 'Oracle', 'Hibah Dikti', 'Dana Internal', 'Donasi Alumni']),
            'acquisition_year' => $this->faker->numberBetween(2015, 2025),
            'placed_in_service_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'fiscal_group' => $this->faker->randomElement(['1', '2', '3', '4']),
            'status' => $this->faker->randomElement(['available', 'borrowed', 'maintenance', 'dikeluarkan']),
            'condition' => $this->faker->randomElement(['good', 'damaged', 'broken']),
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'condition' => 'good',
        ]);
    }

    public function borrowed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'borrowed',
        ]);
    }

    public function configure(): static
    {
        // FIX: Remove the type hint or use the correct Model class
        return $this->afterCreating(function (Item $item) { 
            
            // 1. Attach Categories
            $categories = Category::inRandomOrder()->limit(rand(1, 2))->get();
            if ($categories->isNotEmpty()) {
                $item->categories()->attach($categories);
            }

            // 2. Generate QR Code
            if (!Storage::disk('public')->exists('qrcodes')) {
                Storage::disk('public')->makeDirectory('qrcodes');
            }

            $fileName = 'qr_' . $item->serial_number . '.png';
            $fullPath = storage_path('app/public/qrcodes/' . $fileName);

            try {
                QrCode::format('png')
                      ->size(200)
                      ->margin(1)
                      ->generate($item->serial_number, $fullPath);

                $item->qr_code = 'qrcodes/' . $fileName;
                $item->saveQuietly(); 
                
            } catch (\Exception $e) {
                // Handle exception
            }
        });
    }
}