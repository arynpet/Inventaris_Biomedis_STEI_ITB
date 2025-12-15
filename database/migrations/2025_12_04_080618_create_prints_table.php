<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prints', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->references('id')->on('peminjam_users')
                ->cascadeOnDelete();

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['pending', 'printing', 'done', 'canceled'])
                  ->default('pending');

            $table->string('file_name')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prints');
    }
};
