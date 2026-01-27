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
            $table->string('stl_path')->nullable()->after('file_path'); // Path file STL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prints', function (Blueprint $table) {
            $table->dropColumn('stl_path');
        });
    }
};
