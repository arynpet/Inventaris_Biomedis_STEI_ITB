<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Raden Satya Febry Pangestu',
                'email' => 'radenstya2@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('password'),
                'created_at' => '2025-12-04 17:41:40',
                'updated_at' => '2025-12-04 17:41:40',
            ],

        ]);
    }
}