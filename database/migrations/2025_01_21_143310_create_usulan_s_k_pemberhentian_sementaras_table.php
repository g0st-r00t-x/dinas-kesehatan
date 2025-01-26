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
            $table->string('nama');
            $table->string('nip')->unique();
            $table->string('unit_kerja');
            $table->string('pangkat_golongan');
            $table->date('tmt_sk_pangkat_terakhir');
            $table->date('tmt_sk_jabatan_terakhir');
            $table->string('file_sk_jabatan_fungsional_terakhir');
            $table->enum('alasan', ['tidak_melanjutkan_pendidikan', 'melanjutkan_pendidikan']);
            $table->string('file_pak');
            $table->string('surat_pengantar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_sk_pemberhentian_sementara');
    }
};