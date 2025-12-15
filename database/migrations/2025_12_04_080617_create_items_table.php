<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // No. Asset
            $table->string('asset_number')->nullable();

            // Integrasi dengan tabel rooms
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();

            // Jumlah Unit
            $table->integer('quantity')->default(1);

            // Sumber
            $table->string('source')->nullable();

            // Tahun Perolehan
            $table->year('acquisition_year')->nullable();

            // Date Place in Service
            $table->date('placed_in_service_at')->nullable();

            // Kelompok Fiskal
            $table->string('fiscal_group')->nullable();

            // Status barang
            $table->enum('status', ['available', 'borrowed', 'maintenance'])
                  ->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

