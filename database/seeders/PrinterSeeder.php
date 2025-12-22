<?php

namespace Database\Seeders;

use App\Models\Printer;
use Illuminate\Database\Seeder;

class PrinterSeeder extends Seeder
{
    public function run(): void
    {
        Printer::insert([
            [
                'name' => 'BambooLab P1S',
                'category' => 'filament',
                'material_type_id' => 'filament',
                'description' => null,
                'status' => 'available',
                'available_at' => null,
                'created_at' => '2025-12-09 09:05:16',
                'updated_at' => '2025-12-09 09:05:16',
            ],
            [
                'name' => 'BambooLab P1S',
                'category' => 'filament',
                'material_type_id' => 'filament',
                'description' => null,
                'status' => 'available',
                'available_at' => null,
                'created_at' => '2025-12-09 09:05:16',
                'updated_at' => '2025-12-09 09:05:16',
            ],
            [
                'name' => 'BambooLab A1',
                'category' => 'filament',
                'material_type_id' => 'filament',
                'description' => null,
                'status' => 'available',
                'available_at' => null,
                'created_at' => '2025-12-09 09:05:50',
                'updated_at' => '2025-12-09 09:05:50',
            ],
            [
                'name' => 'Raise3d Pro2+',
                'category' => 'resin',
                'material_type_id' => 'filament',
                'description' => null,
                'status' => 'available',
                'available_at' => null,
                'created_at' => '2025-12-09 09:06:03',
                'updated_at' => '2025-12-10 01:19:07',
            ],
            [
                'name' => 'FromLabs 3+',
                'category' => 'filament',
                'material_type_id' => 'resin',
                'description' => null,
                'status' => 'available',
                'available_at' => null,
                'created_at' => '2025-12-09 09:06:16',
                'updated_at' => '2025-12-09 09:06:16',
            ],
            [
                'name' => 'Anycubic Photon M2/3',
                'category' => 'resin',
                'material_type_id' => 'filament',
                'description' => null,
                'status' => 'available',
                'available_at' => null,
                'created_at' => '2025-12-09 09:06:32',
                'updated_at' => '2025-12-10 01:18:58',
            ],
        ]);
    }
}