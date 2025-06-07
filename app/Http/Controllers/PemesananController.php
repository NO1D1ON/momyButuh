<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Tambahkan ini untuk menghasilkan UUID

class PemesananController extends Controller
{
    // Fungsi untuk menampilkan halaman Pemesanan (untuk web)
    public function index(Request $request)
    {
        // Mendapatkan nilai pencarian (jika ada)
        $search = $request->input('search');

        // Ambil data pemesanan, dengan pencarian jika ada
        $pemesanan = Pemesanan::when($search, function ($query, $search) {
            // Perbaiki kolom pencarian agar sesuai dengan tabel
            return $query->where('id_pemesanan', 'like', "%$search%") // Ganti 'id' menjadi 'id_pemesanan'
                         ->orWhere('konsumen_id', 'like', "%$search%") // Ini sudah merujuk ke no_identitas konsumens
                         ->orWhere('lapangan_id', 'like', "%$search%");
        })
        ->paginate(10); // Pagination untuk membatasi jumlah data per halaman

        // BARIS PENTING INI UNTUK MENGIRIM VARIABEL $pemesanan KE VIEW
        return view('pemesanan', compact('pemesanan'));
    }

    // Fungsi untuk menyimpan data pemesanan dari API
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Validasi konsumen_id seharusnya merujuk ke no_identitas di tabel konsumens
            'konsumen_id' => 'required|exists:konsumens,no_identitas', // Perbaikan di sini
            'lapangan_id' => 'required|exists:lapangans,id_lapangan',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i:s',
            'jam_selesai' => 'required|date_format:H:i:s|after:jam_mulai',
            'harga' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $pemesanan = Pemesanan::create([
                // 'id_pemesanan' => (string) Str::uuid(), // Menghasilkan UUID baru untuk id_pemesanan
                'konsumen_id' => $request->konsumen_id,
                'lapangan_id' => $request->lapangan_id,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'harga' => $request->harga,
                'catatan' => $request->catatan,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan berhasil ditambahkan',
                'data' => $pemesanan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pemesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexApi(Request $request)
    {
        $search = $request->input('search');

        $pemesanan = Pemesanan::when($search, function ($query, $search) {
            return $query->where('id_pemesanan', 'like', "%$search%") // Ganti 'id' menjadi 'id_pemesanan'
                         ->orWhere('konsumen_id', 'like', "%$search%")
                         ->orWhere('lapangan_id', 'like', "%$search%");
        })
        ->paginate(10); // Pagination untuk data API

        // Mengembalikan data sebagai JSON
        return response()->json($pemesanan);
    }
}