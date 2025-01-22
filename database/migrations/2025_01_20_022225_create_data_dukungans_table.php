<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_data_dukungans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // Jenis data dukungan (contoh: SK Pemberian Tunjangan, Ijazah Terakhir)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_data_dukungans');
    }
};