<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_lapangan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_lapangan',
        'nama_lapangan',
        'lokasi',
        'harga_lapangan',
        'rating',
        'deskripsi_lapangan',
        'gambar_lapangan',
        'status_aktif'
    ];

    // Relasi many-to-many dengan Fasilitas
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'lapangan_fasilitas', 'lapangan_id', 'fasilitas_id');
    }
}