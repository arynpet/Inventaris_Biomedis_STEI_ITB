<?php

namespace Database\Seeders;

use App\Models\PeminjamUser;
use Illuminate\Database\Seeder;

class PeminjamUserSeeder extends Seeder
{
    public function run(): void
    {
        PeminjamUser::insert([
            [
                'name' => 'Raden Satya Febry Pangestu',
                'nim' => '889928819',
                'email' => 'radenstya2@gmail.com',
                'is_trained' => 1,
                'phone' => '0896762667722',
                'role' => 'mahasiswa',
                'created_at' => '2025-12-06 02:56:02',
                'updated_at' => '2025-12-09 01:54:21',
            ],
            [
                'name' => 'Ryan Alvian',
                'nim' => '0088943664',
                'email' => 'laraneedssometea@gmail.com',
                'is_trained' => 1,
                'phone' => '085179712633',
                'role' => 'dosen',
                'created_at' => '2025-12-07 09:41:13',
                'updated_at' => '2025-12-09 06:47:56',
            ],
            [
                'name' => 'Aryan',
                'nim' => '00826382123',
                'email' => 'aryan@gmail.com',
                'is_trained' => 1,
                'phone' => '088272917281',
                'role' => 'mahasiswa',
                'created_at' => '2025-12-10 09:32:13',
                'updated_at' => '2025-12-21 06:53:56',
            ],
        ]);
    }
}