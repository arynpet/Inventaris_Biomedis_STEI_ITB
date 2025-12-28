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
    Schema::table('prints', function (Blueprint $table) {
        // Menambahkan kolom project_name setelah user_id
        $table->string('project_name')->after('user_id')->nullable(); 
    });
}

public function down()
{
    Schema::table('prints', function (Blueprint $table) {
        $table->dropColumn('project_name');
    });
}
};
