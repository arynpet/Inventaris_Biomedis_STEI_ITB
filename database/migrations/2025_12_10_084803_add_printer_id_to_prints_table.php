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
        $table->unsignedBigInteger('printer_id')->nullable()->after('user_id');
        $table->foreign('printer_id')->references('id')->on('printers')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('prints', function (Blueprint $table) {
        $table->dropForeign(['printer_id']);
        $table->dropColumn('printer_id');
    });
}

};
