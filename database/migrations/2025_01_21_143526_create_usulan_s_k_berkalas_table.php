<?php

// Migration file

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usulan_sk_berkalas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->unique();
            $table->string('unit_kerja');
            $table->string('pangkat_golongan');
            $table->string('jabatan');
            $table->date('tmt_sk_pangkat_terakhir');
            $table->date('tanggal_penerbitan_pangkat_terakhir');
            $table->date('tmt_sk_berkala_terakhir');
            $table->date('tanggal_penerbitan_sk_berkala_terakhir');
            $table->string('upload_sk_pangkat_terakhir');
            $table->string('upload_sk_berkala_terakhir');
            $table->string('upload_surat_pengantar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_sk_berkalas');
    }
};