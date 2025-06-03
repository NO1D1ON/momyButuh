<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

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