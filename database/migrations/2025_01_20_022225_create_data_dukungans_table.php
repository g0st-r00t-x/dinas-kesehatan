<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_dukungan', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedInteger('data_dukungan_id')->unique(); // Unique Key
            $table->string('jenis'); // Jenis data dukungan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_dukungan');
    }
};
