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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_jenis_surat');
            $table->string('nomor_surat', 100);
            $table->text('perihal')->nullable();
            $table->string('tujuan_surat', 200)->nullable(); 
            $table->date('tanggal_surat');
            $table->string('file_surat', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_jenis_surat')
            ->references('id')
                ->on('jenis_surat')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
