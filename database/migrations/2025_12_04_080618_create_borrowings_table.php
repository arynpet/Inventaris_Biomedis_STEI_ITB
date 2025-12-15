<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')
                ->references('id')->on('peminjam_users')
                ->cascadeOnDelete();

            $table->date('borrow_date');
            $table->date('return_date')->nullable();

            $table->enum('status', ['borrowed', 'returned', 'late'])
                  ->default('borrowed');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
