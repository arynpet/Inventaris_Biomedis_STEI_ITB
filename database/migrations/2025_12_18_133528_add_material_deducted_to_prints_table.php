<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prints', function (Blueprint $table) {
            $table->boolean('material_deducted')
                  ->default(false)
                  ->after('material_amount');
        });
    }

    public function down(): void
    {
        Schema::table('prints', function (Blueprint $table) {
            $table->dropColumn('material_deducted');
        });
    }
};
