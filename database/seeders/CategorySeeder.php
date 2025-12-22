<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Asset',
                'description' => 'Barang Asset',
                'created_at' => '2025-12-04 20:58:29',
                'updated_at' => '2025-12-04 20:58:29',
            ],
            [
                'name' => 'Riset',
                'description' => null,
                'created_at' => '2025-12-06 02:30:00',
                'updated_at' => '2025-12-06 02:30:00',
            ],
        ]);
    }
}