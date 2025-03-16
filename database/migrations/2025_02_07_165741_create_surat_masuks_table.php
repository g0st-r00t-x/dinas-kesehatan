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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengirim', 200);
            $table->string('jabatan_pengirim', 200)->nullable();
            $table->string('instansi_pengirim', 200)->nullable();
            $table->text('perihal');
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->string('file_surat', 255)->nullable();
            $table->unsignedBigInteger('id_jenis_surat');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
