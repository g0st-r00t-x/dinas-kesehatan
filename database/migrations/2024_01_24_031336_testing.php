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
            $table->unsignedInteger("unit_kerja_id")->unique();
            $table->string('nama', 255);
            $table->timestamps();
        });

        // Tabel Pegawai
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->unsignedInteger('nip')->unique();
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
            $table->unsignedInteger('jenis_cuti_id')->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });


        // Seed data jenis cuti
        DB::table('jenis_cuti')->insert([
            ['jenis_cuti_id' => 1, 'nama' => 'Cuti Tahunan'],
            ['jenis_cuti_id' => 2, 'nama' => 'Cuti Melahirkan'],
            ['jenis_cuti_id' => 3, 'nama' => 'Cuti Sakit'],
            ['jenis_cuti_id' => 4, 'nama' => 'Cuti Alasan Penting'],
            ['jenis_cuti_id' => 5, 'nama' => 'Cuti Besar'],
            ['jenis_cuti_id' => 6, 'nama' => 'Cuti Di Luar Tanggungan']
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