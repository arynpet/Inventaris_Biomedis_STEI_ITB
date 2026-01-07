<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('items', function (Blueprint $table) {
        // Check if index exists before dropping
        // Using raw SQL to safely drop index if it exists
    });
    
    // Safe drop using try-catch or checking information_schema
    try {
        \DB::statement('ALTER TABLE items DROP INDEX unique_asset_number');
    } catch (\Exception $e) {
        // Index doesn't exist, that's fine
    }
}

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Kembalikan jadi unique jika di-rollback
            $table->unique('asset_number');
        });
    }
};