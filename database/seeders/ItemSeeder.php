<?php

namespace Database\Seeders;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'no_asset' => '10142',
                'kode_lokasi' => '2101021.60010001.136.136020400',
                'nama_gedung' => 'LABORATORIUM TEKNOLOGI VIII',
                'nama_ruang' => 'FACULTY LOUNGE',
                'jumlah_unit' => 3,
                'deskripsi' => 'Laptop / Notebook Intel Atom N280',
                'sumber' => 'ORACLE FBDI',
                'tahun_perolehan' => 2009,
                'date_place_in_service' => '2009-10-30',
                'kelompok_fiskal' => 'I',
                'asset_category' => '06.06150.0615001',
                'status' => 'berfungsi',
            ],
            [
                'no_asset' => '10144',
                'kode_lokasi' => '2101021.60010001.125.125010100',
                'nama_gedung' => 'LAB. KONVERSI (STEI)',
                'nama_ruang' => 'TEST BENCHES',
                'jumlah_unit' => 1,
                'deskripsi' => 'Function Generator',
                'sumber' => 'ORACLE FBDI',
                'tahun_perolehan' => 2009,
                'date_place_in_service' => '2009-09-30',
                'kelompok_fiskal' => 'II',
                'asset_category' => '06.06113.0611306',
                'status' => 'berfungsi',
            ],
            [
                'no_asset' => '10145',
                'kode_lokasi' => '2101021.60010001.136.136032500',
                'nama_gedung' => 'LABORATORIUM TEKNOLOGI VIII',
                'nama_ruang' => 'DAPUR',
                'jumlah_unit' => 1,
                'deskripsi' => 'Measurement Tool Spectrum Analizer Electronics - GW Instek GSP-830',
                'sumber' => 'ORACLE FBDI',
                'tahun_perolehan' => 2009,
                'date_place_in_service' => '2009-09-30',
                'kelompok_fiskal' => 'I',
                'asset_category' => '06.06107.0610703',
                'status' => 'berfungsi',
            ],
            [
                'no_asset' => '10149',
                'kode_lokasi' => '2101021.60010001.136.136032500',
                'nama_gedung' => 'LABORATORIUM TEKNOLOGI VIII',
                'nama_ruang' => 'DAPUR',
                'jumlah_unit' => 16,
                'deskripsi' => 'Altera DE1',
                'sumber' => 'ORACLE FBDI',
                'tahun_perolehan' => 2009,
                'date_place_in_service' => '2009-07-03',
                'kelompok_fiskal' => 'I',
                'asset_category' => '06.06107.0610704',
                'status' => 'berfungsi',
            ],
            [
                'no_asset' => '10152',
                'kode_lokasi' => '2101021.60010001.136.136022500',
                'nama_gedung' => 'LABORATORIUM TEKNOLOGI VIII',
                'nama_ruang' => 'RUANG TEKNISI',
                'jumlah_unit' => 3,
                'deskripsi' => 'Digital Signal Processor Texas Instruments DSP Starter Kit TMDS DSK6713',
                'sumber' => 'ORACLE FBDI',
                'tahun_perolehan' => 2009,
                'date_place_in_service' => '2009-07-28',
                'kelompok_fiskal' => 'I',
                'asset_category' => '06.06107.0610704',
                'status' => 'berfungsi',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}

