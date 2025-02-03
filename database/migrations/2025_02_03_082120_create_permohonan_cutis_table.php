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
        // Tabel Permohonan Cuti
        Schema::create('permohonan_cuti', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pegawai_nip');
            $table->unsignedInteger('jenis_cuti_id');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('alasan')->nullable();
            $table->string('status', 50)->default('diajukan');
            $table->timestamps();

            $table
                ->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreign('pegawai_nip')
                ->references('nip')
                ->on('pegawai')
                ->onDelete('cascade');
            $table->foreign('jenis_cuti_id')
                ->references('jenis_cuti_id')
                ->on('jenis_cuti')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_permohonan_cutis');
    }
};
