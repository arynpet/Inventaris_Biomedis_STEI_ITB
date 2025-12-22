<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_types', function (Blueprint $table) {
            $table->string('unit')->change(); 
        });

        DB::table('material_types')->where('unit', 'g')->update(['unit' => 'gram']);
        DB::table('material_types')->where('unit', 'ml')->update(['unit' => 'mililiter']);

        Schema::table('material_types', function (Blueprint $table) {
            $table->enum('unit', ['gram', 'mililiter'])->nullable()->change();
        });

        Schema::table('prints', function (Blueprint $table) {
            $table->string('material_unit')->nullable()->change();
        });

        DB::table('prints')->where('material_unit', 'g')->update(['material_unit' => 'gram']);
        DB::table('prints')->where('material_unit', 'ml')->update(['material_unit' => 'mililiter']);

        Schema::table('prints', function (Blueprint $table) {
            $table->enum('material_unit', ['gram', 'mililiter'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('material_types', function (Blueprint $table) {
            $table->string('unit')->change();
        });
        
        DB::table('material_types')->where('unit', 'gram')->update(['unit' => 'g']);
        
        Schema::table('material_types', function (Blueprint $table) {
            $table->enum('unit', ['g', 'ml'])->default('g')->change();
        });
    }
};