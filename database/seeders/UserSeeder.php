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
                'role' => 'superadmin',
                'is_dev_mode' => true,
                'password' => bcrypt('password'),
                'created_at' => '2025-12-04 17:41:40',
                'updated_at' => '2025-12-04 17:41:40',
            ],

            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'email_verified_at' => '2025-12-17 01:34:11',
                'role' => 'admin',
                'is_dev_mode' => false,
                'password' => bcrypt('password'),
                'created_at' => '2025-12-17 01:34:11',
                'updated_at' => '2025-12-17 01:34:11',
            ],
            [
                'name' => 'superadmin',
                'email' => 'superadmin@example.com',
                'email_verified_at' => '2025-12-17 01:34:11',
                'role' => 'superadmin',
                'is_dev_mode' => true,
                'password' => bcrypt('password'),
                'created_at' => '2025-12-17 01:34:11',
                'updated_at' => '2025-12-17 01:34:11',
            ],
        ]);
    }
}