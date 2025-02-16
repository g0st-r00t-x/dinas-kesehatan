<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekap_absen', function (Blueprint $table) {
            $table->id();
            $table->string('bulan');
            $table->string('upload_excel');
            $table->enum('jenis_pegawai', ['asn', 'non-asn']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rekap_absen_asn');
    }
};

