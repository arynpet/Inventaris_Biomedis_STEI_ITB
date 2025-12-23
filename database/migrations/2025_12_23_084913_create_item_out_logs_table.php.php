<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// Database/migrations/xxxx_create_item_out_logs_table.php
public function up(): void
{
    Schema::create('item_out_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained()->onDelete('cascade');
        $table->string('recipient_name'); // Nama penerima/tujuan
        $table->date('out_date');         // Tanggal keluar
        $table->text('reason')->nullable(); // Alasan pengeluaran
        $table->string('reference_number')->nullable(); // No Surat
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
