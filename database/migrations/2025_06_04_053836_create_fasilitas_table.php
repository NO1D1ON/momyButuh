<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id(); // ID otomatis (integer)
            $table->string('nama_fasilitas')->unique(); // Nama fasilitas, harus unik
            $table->string('gambar_ikon')->nullable(); // Nama file gambar ikon (misal: 'toilet.png')
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};