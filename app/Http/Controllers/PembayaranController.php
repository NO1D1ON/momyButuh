<?php

namespace App\Http\Controllers;

// 1. Pastikan Anda mengimpor model Pembayaran di bagian atas
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data pembayaran dari database dengan paginasi
        //    '10' berarti 10 data per halaman. Anda bisa mengubah angka ini.
        $dataPembayaran = Pembayaran::paginate(10);

        // 3. Kirim variabel $dataPembayaran ke view dengan nama 'pembayaran'
        return view('pembayaran', [
            'pembayaran' => $dataPembayaran
        ]);
    }
}