<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permasalahan_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pegawai_nip');
            $table->text('permasalahan');
            $table->unsignedInteger('data_dukungan_id');
            $table->string('file_upload')->nullable();
            $table->string('surat_pengantar_unit_kerja')->nullable();
            $table->timestamps();

            // Define Foreign Key Constraint
            $table->foreign('data_dukungan_id')
                ->references('data_dukungan_id')
                ->on('data_dukungan')
                ->onDelete('cascade');
            $table->foreign('pegawai_nip')
                ->references('nip')
                ->on('pegawai')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permasalahan_kepegawaian');
    }
};