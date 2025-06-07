// Futsal/2025_06_07_043039_create_pemesanan_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananTable extends Migration
{
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->string('id_pemesanan', 20)->primary(); // Ubah panjangnya jika perlu, tambahkan primary()
            // $table->id(); // Primary key untuk tabel pemesanan

            // Foreign key untuk tabel konsumens
            // Pastikan tipe data sesuai dengan primary key di tabel 'konsumens' (id)
            $table->string('konsumen_id', 255);
            $table->foreign('konsumen_id')->references('no_identitas')->on('konsumens')->onDelete('cascade');

            // Foreign key untuk tabel lapangans
            $table->string('lapangan_id', 255);
            $table->foreign('lapangan_id')->references('id_lapangan')->on('lapangans')->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('harga', 10, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
}