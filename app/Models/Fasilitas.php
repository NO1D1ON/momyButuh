<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $fillable = ['nama_fasilitas', 'gambar_ikon'];

    // Relasi many-to-many dengan Lapangan
    public function lapangans()
    {
        return $this->belongsToMany(Lapangan::class, 'lapangan_fasilitas', 'fasilitas_id', 'lapangan_id');
    }
}