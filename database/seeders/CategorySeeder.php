<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::insert([
            ['nama' => 'Asset'],
            ['nama' => 'Riset'],
            ['nama' => 'Habis'],
            ['nama' => 'Praktikum'],
        ]);
    }

}
