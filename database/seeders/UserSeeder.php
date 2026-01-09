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
                'name' => 'admin',
                'email' => 'admin@example.com',
                'email_verified_at' => '2025-12-17 01:34:11',
                'password' => bcrypt('password'),
                'created_at' => '2025-12-17 01:34:11',
                'updated_at' => '2025-12-17 01:34:11',
            ],
            [
                'name' => 'superadmin',
                'email' => 'superadmin@example.com',
                'email_verified_at' => '2025-12-17 01:34:11',
                'role' => 'superadmin',
                'password' => bcrypt('password'),
                'created_at' => '2025-12-17 01:34:11',
                'updated_at' => '2025-12-17 01:34:11',
            ],
        ]);
    }
}