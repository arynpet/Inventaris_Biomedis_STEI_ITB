<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\PeminjamUser;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\MaterialType;
use App\Models\Room;
use App\Models\User;
use App\Models\ItemOutLog;
use App\Models\Print3D;
use App\Models\Printer;
use App\Models\RoomBorrowing;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
        //     RoomSeeder::class,
        //     CategorySeeder::class,
        //     PeminjamUserSeeder::class,
        //     ItemSeeder::class,
            // CategoryItemSeeder::class,
        //     BorrowingSeeder::class,
        //     RoomBorrowingSeeder::class,
        //     MaterialTypeSeeder::class,
        //     PrinterSeeder::class,
        //     PrintSeeder::class,
        ]);

        Category::factory()->count(10)->create();
        Item::factory()->count(50)->create();
        PeminjamUser::factory()->trained()->count(20)->create();
        MaterialType::factory()->count(2)->create();
        Room::factory()->count(10)->create();
        User::factory()->count(10)->create();
        ItemOutLog::factory()->count(10)->create();
        Print3D::factory()->count(10)->create();
        Printer::factory()->count(20)->create();
        Borrowing::factory()->returned()->count(30)->create();
        RoomBorrowing::factory()->count(10)->create();
    }
}