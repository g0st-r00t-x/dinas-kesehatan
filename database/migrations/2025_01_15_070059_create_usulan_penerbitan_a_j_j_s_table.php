<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('usulan_penerbitan_ajj', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip');
            $table->string('unit_kerja');
            $table->date('tmt_pemberian_tunjangan');
            $table->enum('sk_jabatan', ['ADA', 'TIDAK ADA']);
            $table->string('upload_berkas')->nullable();
            $table->string('surat_pengantar_unit_kerja')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usulan_penerbitan_ajj');
    }
};
