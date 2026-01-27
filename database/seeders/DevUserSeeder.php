<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek jika user sudah ada
        if (!User::where('email', 'dev@app.com')->exists()) {
            User::create([
                'name' => 'Developer',
                'email' => 'dev@app.com',
                'password' => Hash::make('password'),
                'role' => 'dev',
                'is_dev_mode' => true, // Set existing column too for compatibility
            ]);
        }
    }
}
