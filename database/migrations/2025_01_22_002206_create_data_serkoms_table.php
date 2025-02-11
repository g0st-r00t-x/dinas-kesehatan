<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_serkom', function (Blueprint $table) {
            $table->id('id_serkom');
            $table->string('nip', 20)->index(); // NIP pegawai
            $table->string('nama_pegawai', 100); // Nama pegawai
            $table->string('nama_sertifikasi', 255); // Nama sertifikasi
            $table->string('nomor_sertifikat', 50)->unique(); // Nomor sertifikat
            $table->string('lembaga_penerbit', 255); // Lembaga penerbit sertifikasi
            $table->date('tanggal_terbit'); // Tanggal penerbitan
            $table->date('tanggal_kadaluarsa')->nullable(); // Tanggal kadaluarsa (opsional)
            $table->enum('status_validasi', ['Valid', 'Expired', 'Pending'])->default('Pending'); // Status sertifikasi
            $table->string('file_sertifikat', 255)->nullable(); // Path file sertifikat
            $table->timestamps(); // created_at & updated_at

            // Jika ada tabel pegawai, tambahkan foreign key
            // $table->foreign('nip')->references('nip')->on('pegawai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_serkom');
    }
};
