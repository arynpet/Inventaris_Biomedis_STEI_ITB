<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_borrowings', function (Blueprint $table) {
            $table->id();

            // ruangan yang dipinjam
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // peminjam (sesuaikan dengan tabel user peminjam kamu)
            $table->foreignId('user_id')->constrained('peminjam_users');

            // waktu pinjam
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->string('purpose')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, finished
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_borrowings');
    }
};
