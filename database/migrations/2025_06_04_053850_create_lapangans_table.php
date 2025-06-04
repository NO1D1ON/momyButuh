<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangans', function (Blueprint $table) {
            $table->string('id_lapangan')->primary(); // ID kustom seperti 'LP001'
            $table->string('nama_lapangan');
            $table->string('lokasi');
            $table->integer('harga_lapangan');
            $table->float('rating')->default(0.0);
            $table->text('deskripsi_lapangan')->nullable();
            $table->string('gambar_lapangan')->nullable(); // Nama file gambar lapangan
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};