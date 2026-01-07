<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('items', function (Blueprint $table) {
        // Hapus index unique berdasarkan NAMANYA yang ada di SQL dump kamu
        $table->dropIndex('unique_asset_number'); 
    });
}

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Kembalikan jadi unique jika di-rollback
            $table->unique('asset_number');
        });
    }
};