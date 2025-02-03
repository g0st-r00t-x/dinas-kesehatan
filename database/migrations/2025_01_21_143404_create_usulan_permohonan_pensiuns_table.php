<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usulan_pensiun', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pegawai_nip');
            $table->string('surat_pengantar_unit');
            $table->string('sk_pangkat_terakhir');
            $table->string('sk_cpcns');
            $table->string('sk_pns');
            $table->string('sk_berkala_terakhir');
            $table->string('akte_nikah');
            $table->string('ktp_pasangan');
            $table->string('karis_karsu');
            $table->string('skp_terakhir');
            $table->string('akte_anak')->nullable();
            $table->string('surat_kuliah')->nullable();
            $table->string('akte_kematian')->nullable();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('npwp');
            $table->string('foto');
            $table->string('nomor_telepon');
            $table->timestamps();

            $table
                ->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreign('pegawai_nip')
                ->references('nip')
                ->on('pegawai')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usulan_pensiun');
    }
};