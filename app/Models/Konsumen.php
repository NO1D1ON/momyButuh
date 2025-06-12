<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

    // KASIH TAU LARAVEL PRIMARY KEY-NYA APA (Ini sudah benar)
    protected $primaryKey = 'no_identitas';

    // KASIH TAU KALO KEY-NYA BUKAN AUTO-INCREMENT (Ini sudah benar)
    public $incrementing = false;

    // KASIH TAU TIPE KEY-NYA STRING (Ini sudah benar)
    protected $keyType = 'string';

    protected $fillable = [
        'no_identitas', // Tetap di sini agar bisa diisi oleh kode kita
        'nama',
        'email',
        'no_telepon',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Boot the model.
     * Secara otomatis akan mengisi 'no_identitas' saat membuat konsumen baru.
     */
    protected static function booted(): void
    {
        static::creating(function (Konsumen $konsumen) {
            // 1. Cari konsumen terakhir berdasarkan no_identitas
            $latestKonsumen = self::orderBy('no_identitas', 'desc')->first();

            if (! $latestKonsumen) {
                // Jika tidak ada konsumen sama sekali, mulai dari USR001
                $konsumen->no_identitas = 'USR001';
            } else {
                // 2. Ambil nomor dari ID terakhir (misal: dari 'USR005' ambil angka 5)
                $lastNumber = (int) substr($latestKonsumen->no_identitas, 3);

                // 3. Tambah 1 ke nomor tersebut
                $newNumber = $lastNumber + 1;

                // 4. Format kembali menjadi string dengan 3 digit (misal: 6 -> '006')
                //    dan gabungkan dengan prefix 'USR'
                $konsumen->no_identitas = 'USR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}