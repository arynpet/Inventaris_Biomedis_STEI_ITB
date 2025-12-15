<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjam_users', function (Blueprint $table) {
            $table->boolean('is_trained')
                  ->default(false)
                  ->after('email'); // sesuaikan posisi field
        });
    }

    public function down(): void
    {
        Schema::table('peminjam_users', function (Blueprint $table) {
            $table->dropColumn('is_trained');
        });
    }
};
