<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->string('action');      // create, update, delete, login, etc
        $table->string('model');       // Nama Model (Misal: Print3D, Inventory)
        $table->unsignedBigInteger('model_id')->nullable(); // ID data yang diubah
        $table->text('description')->nullable(); // Detail perubahan (Misal: Mengubah status dari pending ke done)
        $table->string('ip_address')->nullable();
        $table->timestamps();
    });
}
};
