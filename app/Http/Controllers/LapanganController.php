<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan; // Pastikan model Lapangan sudah di-import

class LapanganController extends Controller
{
    public function Index(Request $request)
    {
        $query = Lapangan::query();

        // Logika Pencarian
        // Memastikan parameter 'search' ada dan tidak kosong
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            // Menggunakan where dan orWhere untuk mencari di beberapa kolom
            $query->where(function($q) use ($search) {
                // Mencari berdasarkan ID Lapangan
                $q->where('id_lapangan', 'like', '%' . $search . '%')
                  // Mencari berdasarkan Nama Lapangan
                  ->orWhere('nama_lapangan', 'like', '%' . $search . '%')
                  // Tambahan: Mencari berdasarkan Lokasi
                  ->orWhere('lokasi', 'like', '%' . $search . '%')
                  // Tambahan: Mencari berdasarkan Deskripsi Lapangan
                  ->orWhere('deskripsi_lapangan', 'like', '%' . $search . '%');
            });
        }

        // Paginasi: Ambil 10 data per halaman (sesuaikan jika perlu)
        $lapangans = $query->paginate(10);

        return view('lapangan.index', compact('lapangans'));
    }
    
    // Anda bisa menambahkan middleware 'auth' di constructor jika diperlukan
    public function __construct()
    {
        // $this->middleware('auth');
    }

    // public function index()
    // {
    //     return view('lapangan.index');
    // }
}