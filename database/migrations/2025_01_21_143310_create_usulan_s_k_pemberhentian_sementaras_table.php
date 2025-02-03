<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usulan_sk_pemberhentian_sementara', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pegawai_nip');
            $table->date('tmt_sk_pangkat_terakhir');
            $table->date('tmt_sk_jabatan_terakhir');
            $table->string('file_sk_jabatan_fungsional_terakhir');
            $table->enum('alasan', ['tidak_melanjutkan_pendidikan', 'melanjutkan_pendidikan']);
            $table->string('file_pak');
            $table->string('surat_pengantar');
            $table->timestamps();

            $table
                ->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreign('pegawai_nip')
                ->references('nip')
                ->on('pegawai')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_sk_pemberhentian_sementara');
    }
};