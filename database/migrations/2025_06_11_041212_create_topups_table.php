// database/migrations/xxxx_xx_xx_xxxxxx_create_topups_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topups', function (Blueprint $table) {
            // ID kita buat string untuk menampung format 'TP001'
            $table->string('id')->primary();
            

            // Foreign key ke tabel konsumen
            $table->string('konsumen_id', 255);
            $table->foreign('konsumen_id')->references('no_identitas')->on('konsumens')->onDelete('cascade');
            $table->decimal('nominal', 15, 2); // Kolom untuk nominal, presisi untuk angka besar
            $table->string('status')->default('Pending'); // Status default adalah 'Pending'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topups');
    }
};