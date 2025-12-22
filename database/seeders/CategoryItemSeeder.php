<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('category_item')->insert([
            ['category_id' => 2, 'item_id' => 4],
            ['category_id' => 1, 'item_id' => 4],
            ['category_id' => 1, 'item_id' => 7],
            ['category_id' => 1, 'item_id' => 6],
            ['category_id' => 1, 'item_id' => 8],
            ['category_id' => 1, 'item_id' => 9],
            ['category_id' => 1, 'item_id' => 10],
            ['category_id' => 2, 'item_id' => 10],
            ['category_id' => 1, 'item_id' => 11],
            ['category_id' => 2, 'item_id' => 11],
            ['category_id' => 2, 'item_id' => 12],
        ]);
    }
}