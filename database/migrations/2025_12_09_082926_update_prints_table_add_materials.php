<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prints', function (Blueprint $table) {

            // Durasi print

            // Relasi ke jenis bahan
            $table->foreignId('material_type_id')
                  ->nullable()
                  ->constrained('material_types')
                  ->nullOnDelete()
                  ->after('duration');

            // Jumlah bahan + satuan
            $table->float('material_amount')->nullable()->after('material_type_id');
            $table->enum('material_unit', ['gram', 'ml'])->nullable()->after('material_amount');

            // Sumber bahan
            $table->enum('material_source', ['lab', 'penelitian', 'dosen', 'pribadi'])
                  ->nullable()
                  ->after('material_unit');

            // Upload file bukti dokumen
            $table->string('file_path')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('prints', function (Blueprint $table) {
            $table->dropForeign(['material_type_id']);
            $table->dropColumn([
                'duration',
                'material_type_id',
                'material_amount',
                'material_unit',
                'material_source',
                'file_path',
            ]);
        });
    }
};
