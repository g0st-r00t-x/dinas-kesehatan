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
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            // Add new columns for the enhanced flow
            $table->string('kode_jenis_surat', 20)->nullable();
            $table->string('no_referensi', 50);
            $table->json('data_pengajuan')->nullable();
            $table->timestamp('tgl_persetujuan')->nullable();
            $table->timestamp('tgl_penolakan')->nullable();
            $table->foreignId('id_penyetuju')->nullable();
            $table->foreignId('id_penolak')->nullable();
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->string('file_surat')->nullable();

            // Add foreign key constraint for jenis_surat
            $table->foreign('kode_jenis_surat')->references('kode_jenis')->on('jenis_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['kode_jenis_surat']);
            $table->dropForeign(['id_penyetuju']);
            $table->dropForeign(['id_penolak']);

            // Drop columns
            $table->dropColumn([
                'kode_jenis_surat',
                'no_referensi',
                'data_pengajuan',
                'tgl_persetujuan',
                'tgl_penolakan',
                'id_penyetuju',
                'id_penolak',
                'catatan',
                'alasan_penolakan',
                'file_surat'
            ]);
        });
    }
};
