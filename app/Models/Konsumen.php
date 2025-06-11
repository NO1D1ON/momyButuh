<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

    // KASIH TAU LARAVEL PRIMARY KEY-NYA APA
    protected $primaryKey = 'no_identitas';

    // KASIH TAU KALO KEY-NYA BUKAN AUTO-INCREMENT
    public $incrementing = false;

    // KASIH TAU TIPE KEY-NYA STRING
    protected $keyType = 'string';

    protected $fillable = [
        'no_identitas',
        'nama',
        'email',
        'no_telepon',
        'password',
    ];

    // Opsional: Sembunyikan atribut password saat di-serialize
    protected $hidden = [
        'password',
    ];
}