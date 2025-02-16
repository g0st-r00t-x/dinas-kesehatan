<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usulan_rekomendasi_penelitian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('judul_penelitian');
            $table->string('asal_lembaga_pendidikan');
            $table->enum('tujuan_penelitian', [
                'magang_pkl', 
                'penyusunan_tesis', 
                'penyusunan_skripsi', 
                'penyusunan_riset'
            ]);
            $table->string('nim_nip');
            $table->string('surat_pengantar_path')->nullable();
            $table->string('no_telp_wa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekomendasi_penelitian');
    }
};