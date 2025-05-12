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
        Schema::table('surat_keluar', function (Blueprint $table) {
            // Add new columns for the enhanced flow
            $table->string('kode_jenis_surat', 20)->nullable();
            $table->unsignedInteger('nomor_urut')->nullable();
            $table->foreignId('id_pengajuan')->nullable();
            $table->string('path_file')->nullable();
            $table->foreignId('created_by')->nullable();

            // Add foreign key constraint for jenis_surat
            $table->foreign('kode_jenis_surat')->references('kode_jenis')->on('jenis_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['kode_jenis_surat']);
            $table->dropForeign(['id_pengajuan']);
            $table->dropForeign(['created_by']);

            // Drop columns
            $table->dropColumn([
                'kode_jenis_surat',
                'nomor_urut',
                'id_pengajuan',
                'path_file',
                'created_by'
            ]);
        });
    }
};
