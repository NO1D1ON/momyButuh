<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    use HasFactory;

    // Kunci primer kita bukan integer, jadi kasih tau Laravel
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'id',
        'konsumen_id',
        'nominal',
        'status',
    ];

    /**
     * The "booted" method of the model.
     * Ini magic-nya buat bikin ID otomatis.
     */
    protected static function booted(): void
    {
        static::creating(function (Topup $topup) {
            // Cari ID topup terakhir
            $latestTopup = static::latest('id')->first();
            
            if (!$latestTopup) {
                // Kalo belum ada data sama sekali
                $nextIdNumber = 1;
            } else {
                // Ambil angka dari ID terakhir (misal: dari 'TP001' jadi '1')
                $lastIdNumber = (int) substr($latestTopup->id, 2);
                $nextIdNumber = $lastIdNumber + 1;
            }
            
            // Format ID baru jadi 'TP' + 3 digit angka (misal: 'TP001')
            $topup->id = 'TP' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relasi ke model Konsumen.
     * Satu topup punya satu konsumen.
     */
    public function konsumen()
    {
        // Foreign key-nya 'konsumen_id', primary key di tabel konsumen itu 'no_identitas'
        return $this->belongsTo(Konsumen::class, 'konsumen_id', 'no_identitas');
    }
}