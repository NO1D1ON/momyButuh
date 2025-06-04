<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        Fasilitas::create(['nama_fasilitas' => 'Toilet', 'gambar_ikon' => 'toilet.png']);
        Fasilitas::create(['nama_fasilitas' => 'Parkiran', 'gambar_ikon' => 'parkiran.png']);
        Fasilitas::create(['nama_fasilitas' => 'Kantin', 'gambar_ikon' => 'kantin.png']);
        // Fasilitas::create(['nama_fasilitas' => 'WiFi', 'gambar_ikon' => 'wifi.png']);
        // Pastikan Anda menempatkan file gambar ini di public/assets/fasilitas/
        // Contoh: toilet.png, parkiran.png, kantin.png, wifi.png
    }
}