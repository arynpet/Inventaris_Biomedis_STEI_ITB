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
        Schema::table('peminjam_users', function (Blueprint $table) {
            // Kita set nullable dulu agar tidak error jika ada data existing
            // Nanti bisa diisi manual atau dibiarkan null
            $table->string('password')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjam_users', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};
