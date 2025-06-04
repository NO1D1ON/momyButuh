<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangan_fasilitas', function (Blueprint $table) {
            $table->id();
            // Foreign key untuk lapangans (string)
            $table->string('lapangan_id');
            $table->foreign('lapangan_id')->references('id_lapangan')->on('lapangans')->onDelete('cascade');

            // Foreign key untuk fasilitas (integer)
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            $table->timestamps();

            // Memastikan kombinasi unik antara lapangan dan fasilitas
            $table->unique(['lapangan_id', 'fasilitas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangan_fasilitas');
    }
};