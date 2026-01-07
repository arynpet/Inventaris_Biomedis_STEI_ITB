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
            'status' => $this->faker->randomElement(['available', 'borrowed', 'maintenance']),
            'condition' => $this->faker->randomElement(['good', 'damaged', 'broken']),
            'qr_code' => null, // Set null dulu, akan di-generate di afterCreating
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
        return $this->afterCreating(function (Item $item) {
            // 1. Attach Categories (optional)
            if (Category::count() > 0) {
                $categories = Category::inRandomOrder()->limit(rand(1, 2))->get();
                if ($categories->isNotEmpty()) {
                    $item->categories()->attach($categories);
                }
            }

            // 2. Generate QR Code (simplified untuk testing)
            try {
                // Pastikan direktori exists
                if (!Storage::disk('public')->exists('qr/items')) {
                    Storage::disk('public')->makeDirectory('qr/items');
                }

                $timestamp = (int)(microtime(true) * 10000); // Mikrodetik untuk uniqueness
                $randomSuffix = \Illuminate\Support\Str::random(6); // Random string untuk mencegah collision
                $qrPath = 'qr/items/' . $item->id . '-' . $timestamp . '-' . $randomSuffix . '.svg';
                
                // Load relasi yang dibutuhkan
                $item->load('room');
                $roomName = $item->room ? $item->room->name : 'N/A';

                $qrPayload = "Item Name: " . $item->name . "\r\n" .
                         "Asset No: " . ($item->asset_number ?? 'N/A') . "\r\n" .
                         "Serial No: " . $item->serial_number . "\r\n" .
                         "Room Name: " . $roomName . "\r\n" .
                         "Condition: " . $item->condition;

                $qrContent = QrCode::format('svg')
                    ->size(300)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($qrPayload);

                Storage::disk('public')->put($qrPath, $qrContent);
                
                // Update tanpa trigger event lagi
                $item->updateQuietly(['qr_code' => $qrPath]);
                
            } catch (\Exception $e) {
                // Silent fail untuk testing - QR code bukan critical
                \Log::warning('QR Code generation failed in factory: ' . $e->getMessage());
            }
        });
    }
}