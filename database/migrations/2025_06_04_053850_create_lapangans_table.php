<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Babysitter', function (Blueprint $table) {
            $table->string('id_Babysitter')->primary(); // ID kustom seperti 'LP001'
            $table->string('nama_Babysitter');
            $table->string('alamat_Babysitter');
            $table->integer('harga_Per_Jam');
            $table->float('rating')->default(0.0);
            $table->text('deskripsi_Babysitter')->nullable();
            $table->string('foto_Babysitter')->nullable(); // Nama file gambar lapangan
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Babysitter');
    }
};