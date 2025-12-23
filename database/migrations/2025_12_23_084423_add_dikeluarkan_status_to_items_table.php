<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cara paling aman menggunakan DB Statement agar tidak menghapus data lama
        DB::statement("ALTER TABLE items MODIFY COLUMN status ENUM('available', 'borrowed', 'maintenance', 'dikeluarkan') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke status semula jika di-rollback
        DB::statement("ALTER TABLE items MODIFY COLUMN status ENUM('available', 'borrowed', 'maintenance') NOT NULL DEFAULT 'available'");
    }
};