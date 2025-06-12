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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            // UBAH BAGIAN INI
            // $table->morphs('tokenable'); <-- Hapus atau komentari baris ini
            // GANTI DENGAN DUA BARIS INI
            $table->string("tokenable_type");
            $table->string("tokenable_id"); // <-- Kita ubah menjadi string
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // TAMBAHKAN INDEX UNTUK PERFORMA
            $table->index(["tokenable_type", "tokenable_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
