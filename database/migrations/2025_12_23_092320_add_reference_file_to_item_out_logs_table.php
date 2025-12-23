<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('item_out_logs', function (Blueprint $table) {
        // Ganti reference_number jadi reference_file, atau tambah baru
        $table->string('reference_file')->nullable()->after('reason'); 
        
        // Opsional: Jika reference_number mau dihapus
        // $table->dropColumn('reference_number');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_out_logs', function (Blueprint $table) {
            //
        });
    }
};
