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
            $table->string('nama');
            $table->string('nip')->unique();
            $table->string('pangkat_golongan');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->text('permasalahan');
            $table->unsignedBigInteger('data_dukungan_id'); // Foreign Key
            $table->string('file_upload')->nullable();
            $table->string('surat_pengantar_unit_kerja')->nullable();
            $table->timestamps();

            // Define Foreign Key Constraint
            $table->foreign('data_dukungan_id')->references('id')->on('jenis_data_dukungans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permasalahan_kepegawaian');
    }
};