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
            $table->enum('status_pengajuan', ['Belum Diajukan', 'Diajukan', 'Diterima', 'Ditolak'])->default('Belum Diajukan');
            $table->timestamp('tgl_pengajuan')->nullable();
            $table->timestamp('tgl_diterima')->nullable();
            $table->timestamps();

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
