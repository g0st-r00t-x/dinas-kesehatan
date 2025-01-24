<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Unit Kerja
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->timestamps();
        });

        // Tabel Pegawai
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('nip', 50)->unique();
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja');
            $table->string('pangkat_golongan', 50)->nullable();
            $table->string('jabatan', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('status_kepegawaian', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // Tabel Jenis Cuti
        Schema::create('jenis_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel Permohonan Cuti
        Schema::create('permohonan_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai');
            $table->foreignId('jenis_cuti_id')->constrained('jenis_cuti');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('alasan')->nullable();
            $table->string('status', 50)->default('diajukan'); // misalnya: diajukan, disetujui, ditolak
            $table->timestamps();
        });

        // Tabel Dokumen Pendukung Cuti
        Schema::create('dokumen_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_cuti_id')->constrained('permohonan_cuti');
            $table->string('jenis_dokumen', 100);
            $table->string('path_file')->nullable();
            $table->string('nama_file')->nullable();
            $table->timestamps();
        });

        // Seed data jenis cuti
        DB::table('jenis_cuti')->insert([
            ['nama' => 'Cuti Tahunan'],
            ['nama' => 'Cuti Melahirkan'],
            ['nama' => 'Cuti Sakit'],
            ['nama' => 'Cuti Alasan Penting'],
            ['nama' => 'Cuti Besar'],
            ['nama' => 'Cuti Di Luar Tanggungan']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_cuti');
        Schema::dropIfExists('permohonan_cuti');
        Schema::dropIfExists('jenis_cuti');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('unit_kerja');
    }
};