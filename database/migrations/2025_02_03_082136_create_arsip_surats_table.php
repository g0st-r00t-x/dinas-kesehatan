<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_surat')->constrained('pengajuan_surat')->onDelete('cascade');
            $table->string('file_surat_path');
            $table->timestamp('tgl_arsip')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_surat');
    }
};
