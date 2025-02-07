<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengajuan')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->enum('jenis_surat', ['Surat Masuk', 'Surat Keluar'])->default('Surat Masuk');
            $table->string('perihal')->nullable();
            $table->enum('status_pengajuan', ['Diajukan', 'Diterima', 'Ditolak'])->default('Diajukan');
            $table->timestamp('tgl_pengajuan')->nullable();
            $table->timestamp('tgl_diterima')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id')->on('permohonan_cuti')->onDelete('cascade');
            $table
                ->foreignId('id_pemohon')
                ->constrained('users')
                ->onDelete('cascade');
            $table
                ->foreignId('id_diajukan')
                ->constrained('pegawai')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
