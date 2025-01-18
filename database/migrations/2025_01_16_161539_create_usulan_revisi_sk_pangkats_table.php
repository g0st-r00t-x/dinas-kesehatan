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
        Schema::create('usulan_revisi_sk_pangkat', function (Blueprint $table) {
            $table->id('id_usulan');
            $table->string('id_user')->nullable();
            $table->string('nama');
            $table->string('nip', 50);
            $table->string('pangkat_golongan', 50)->nullable();
            $table->text('alasan_revisi_sk');
            $table->text('kesalahan_tertulis_sk');
            $table->string('upload_sk_salah')->nullable();
            $table->string('upload_data_dukung')->nullable();
            $table->string('surat_pengantar')->nullable();
            $table->string('no_wa', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_revisi_sk_pangkat');
    }
};