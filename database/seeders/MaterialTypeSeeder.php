<?php

namespace Database\Seeders;

use App\Models\MaterialType;
use Illuminate\Database\Seeder;

class MaterialTypeSeeder extends Seeder
{
    public function run(): void
    {
        MaterialType::insert([
            [
                'category' => 'resin',
                'name' => 'High Clear',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:18:39',
                'updated_at' => '2025-12-09 03:18:39',
            ],
            [
                'category' => 'resin',
                'name' => 'Tough 1000',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:19:08',
                'updated_at' => '2025-12-09 03:19:08',
            ],
            [
                'category' => 'resin',
                'name' => 'Flexible 80 XA',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:19:24',
                'updated_at' => '2025-12-09 03:19:24',
            ],
            [
                'category' => 'filament',
                'name' => 'ABS',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:20:26',
                'updated_at' => '2025-12-09 03:20:26',
            ],
            [
                'category' => 'filament',
                'name' => 'PLA',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:20:41',
                'updated_at' => '2025-12-09 03:20:41',
            ],
            [
                'category' => 'filament',
                'name' => 'PETG',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:21:05',
                'updated_at' => '2025-12-09 03:21:05',
            ],
            [
                'category' => 'filament',
                'name' => 'PVA',
                'stock_balance' => 0.00,
                'unit' => 'gram',
                'created_at' => '2025-12-09 03:21:19',
                'updated_at' => '2025-12-09 03:21:19',
            ],
        ]);
    }
}