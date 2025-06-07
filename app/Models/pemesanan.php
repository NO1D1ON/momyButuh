<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan'; // Nama tabel yang benar

    protected $primaryKey = 'id_pemesanan'; // Primary key yang benar
    public $incrementing = false; // Karena id_pemesanan adalah string
    protected $keyType = 'string'; // Tipe data primary key adalah string

    protected $fillable = [
        'konsumen_id',
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'harga',
        'catatan',
    ];

    // Boot method untuk event model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pemesanan) {
            // Ambil ID pemesanan terakhir
            $latestPemesanan = static::orderBy('id_pemesanan', 'desc')->first();
            $nextNumber = 1;

            if ($latestPemesanan) {
                // Ekstrak angka dari ID terakhir (misal: PN001 -> 1)
                $lastIdNumber = (int) substr($latestPemesanan->id_pemesanan, 2); // Ambil angka setelah 'PN'
                $nextNumber = $lastIdNumber + 1;
            }

            // Format angka menjadi 3 digit dengan padding nol
            $pemesanan->id_pemesanan = 'PN' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    // Relasi ke model Konsumen
    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'konsumen_id', 'no_identitas');
    }

    // Relasi ke model Lapangan
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id', 'id_lapangan');
    }
}