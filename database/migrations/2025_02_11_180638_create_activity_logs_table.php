<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->string('description');
            $table->nullableMorphs('subject'); // Untuk model yang dimodifikasi
            $table->nullableMorphs('causer');  // Untuk user yang melakukan aksi
            $table->json('properties')->nullable(); // Untuk menyimpan data tambahan
            $table->string('event'); // created, updated, deleted, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
