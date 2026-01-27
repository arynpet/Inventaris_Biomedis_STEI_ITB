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
        Schema::table('prints', function (Blueprint $table) {
            $table->string('lecturer_name')->nullable()->after('material_source'); // Nama Dosen (jika sumber = dosen)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prints', function (Blueprint $table) {
            $table->dropColumn('lecturer_name');
        });
    }
};
