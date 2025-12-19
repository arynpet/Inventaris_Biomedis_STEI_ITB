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
    Schema::table('borrowings', function (Blueprint $table) {
        // Menambahkan kolom kondisi pengembalian
        $table->enum('return_condition', ['good', 'damaged', 'broken'])
              ->nullable() // Nullable karena saat dipinjam belum ada kondisi kembali
              ->after('return_date');
    });
}

public function down()
{
    Schema::table('borrowings', function (Blueprint $table) {
        $table->dropColumn('return_condition');
    });
}
};
