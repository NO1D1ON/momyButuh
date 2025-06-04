<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lapangan;
use App\Models\Fasilitas;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan fasilitas sudah ada sebelum seeding lapangan
        $toilet = Fasilitas::where('nama_fasilitas', 'Toilet')->first();
        $parkiran = Fasilitas::where('nama_fasilitas', 'Parkiran')->first();
        $kantin = Fasilitas::where('nama_fasilitas', 'Kantin')->first();

        $lapangan1 = Lapangan::create([
            'id_lapangan' => 'LP001',
            'nama_lapangan' => 'Futsal Gue',
            'lokasi' => 'Jalan Tuasan, Medan No.80',
            'harga_lapangan' => 60000,
            'rating' => 5.0,
            'deskripsi_lapangan' => 'Tempat bersih dan fasilitas lengkap,tanpa pungli Ada pungli',
            'gambar_lapangan' => 'lapangan1.jpg',
            'status_aktif' => true,
        ]);
        if ($parkiran && $toilet) {
            $lapangan1->fasilitas()->attach([$parkiran->id, $toilet->id]);
        }

        $lapangan2 = Lapangan::create([
            'id_lapangan' => 'LP002',
            'nama_lapangan' => 'Futsal Anu',
            'lokasi' => 'Tembung',
            'harga_lapangan' => 25000,
            'rating' => 0.5,
            'deskripsi_lapangan' => '',
            'gambar_lapangan' => 'lapangan2.jpg',
            'status_aktif' => false,
        ]);
        if ($kantin && $toilet) {
            $lapangan2->fasilitas()->attach([$kantin->id, $toilet->id]);
        }
        // Pastikan Anda menempatkan file gambar ini di public/assets/lapangan/
        // Contoh: lapangan1.jpg, lapangan2.jpg
    }
}