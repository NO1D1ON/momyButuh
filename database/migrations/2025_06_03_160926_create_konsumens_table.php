<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konsumens', function (Blueprint $table) {
            $table->string('no_identitas')->primary(); // ID kustom seperti 'LP001'
            $table->string('nama');
            $table->string('Alamat');
            $table->string('Jumlah_Anak');
            $table->string('Kriteria');
            $table->string('email')->unique();
            $table->string('no_telepon')->nullable();
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('password'); // Password dari mobile, akan di-hash
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsumens');
    }
};