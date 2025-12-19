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
                         Schema::table('items', function (Blueprint $table) {
            // Menambahkan kolom condition setelah status
            // Pilihan: good (Baik), damaged (Rusak Ringan/Perlu Perbaikan), broken (Rusak Berat/Mati)
$table->enum('condition', ['good', 'damaged', 'broken'])
         ->default('good')
                                          ->after('status');
        });
}

  public function down()
                      {   
                                    Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('condition');
                  });
    }
};
