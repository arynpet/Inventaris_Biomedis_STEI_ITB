<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('peminjam_users')->onDelete('cascade');

            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');

            // Details
            $table->integer('quantity')->default(1);
            $table->text('purpose')->nullable();

            // Dates
            $table->date('borrow_date');
            $table->date('return_date')->nullable();

            // Status & Notes
            $table->enum('status', ['pending', 'active', 'returned', 'rejected', 'overdue'])->default('pending');
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
