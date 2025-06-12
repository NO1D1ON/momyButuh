<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model; // <-- HAPUS ATAU GANTI INI
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- TAMBAHKAN INI
use Illuminate\Notifications\Notifiable; // <-- TAMBAHKAN INI (Praktik yang baik)
use Laravel\Sanctum\HasApiTokens; // <-- TAMBAHKAN INI

class Konsumen extends Authenticatable // <-- UBAH "Model" MENJADI "Authenticatable"
{
    // TAMBAHKAN trait yang diperlukan untuk otentikasi dan token
    use HasFactory, HasApiTokens, Notifiable;

    // KASIH TAU LARAVEL PRIMARY KEY-NYA APA (Ini sudah benar dan kita pertahankan)
    protected $primaryKey = 'no_identitas';

    // KASIH TAU KALO KEY-NYA BUKAN AUTO-INCREMENT (Ini sudah benar dan kita pertahankan)
    public $incrementing = false;

    // KASIH TAU TIPE KEY-NYA STRING (Ini sudah benar dan kita pertahankan)
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_identitas', // Tetap di sini agar bisa diisi oleh kode kita
        'nama',
        'email',
        'no_telepon',
        'saldo',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Praktik yang baik untuk menyembunyikan ini juga
    ];

    /**
     * Boot the model.
     * Secara otomatis akan mengisi 'no_identitas' saat membuat konsumen baru.
     * (Logika cerdas ini kita pertahankan sepenuhnya!)
     */
    protected static function booted(): void
    {
        static::creating(function (Konsumen $konsumen) {
            // Jika no_identitas belum diisi, maka buat otomatis
            if (empty($konsumen->no_identitas)) {
                $latestKonsumen = self::orderBy('no_identitas', 'desc')->first();

                if (!$latestKonsumen) {
                    $konsumen->no_identitas = 'USR001';
                } else {
                    $lastNumber = (int) substr($latestKonsumen->no_identitas, 3);
                    $newNumber = $lastNumber + 1;
                    $konsumen->no_identitas = 'USR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }
}