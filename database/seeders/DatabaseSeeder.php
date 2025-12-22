<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            RoomSeeder::class,
            CategorySeeder::class,
            PeminjamUserSeeder::class,
            ItemSeeder::class,
            CategoryItemSeeder::class,
            BorrowingSeeder::class,
            RoomBorrowingSeeder::class,
            MaterialTypeSeeder::class,
            PrinterSeeder::class,
            PrintSeeder::class,
        ]);
    }
}