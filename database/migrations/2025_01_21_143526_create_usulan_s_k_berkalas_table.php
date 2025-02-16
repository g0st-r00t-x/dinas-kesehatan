<?php

// Migration file

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usulan_sk_berkala', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pegawai_nip');
            $table->date('tmt_sk_pangkat_terakhir');
            $table->date('tanggal_penerbitan_pangkat_terakhir');
            $table->date('tmt_sk_berkala_terakhir');
            $table->date('tanggal_penerbitan_sk_berkala_terakhir');
            $table->string('upload_sk_pangkat_terakhir');
            $table->string('upload_sk_berkala_terakhir');
            $table->string('upload_surat_pengantar');
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

    public function down(): void
    {
        Schema::dropIfExists('usulan_sk_berkalas');
    }
};