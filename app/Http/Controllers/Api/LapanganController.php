<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Fasilitas; // Import Fasilitas model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::with('fasilitas')->get();

        $formattedLapangans = $lapangans->map(function ($lapangan) {
            return [
                'id' => $lapangan->id_lapangan,
                'nama_lapangan' => $lapangan->nama_lapangan,
                'lokasi' => $lapangan->lokasi,
                'harga_lapangan' => $lapangan->harga_lapangan,
                'rating' => $lapangan->rating,
                'deskripsi_lapangan' => $lapangan->deskripsi_lapangan,
                'gambar_lapangan' => $lapangan->gambar_lapangan,
                'full_gambar_url' => $lapangan->gambar_lapangan ? asset('assets/lapangan/' . $lapangan->gambar_lapangan) : null,
                'fasilitas' => $lapangan->fasilitas->map(function ($fasilitas) {
                    return [
                        'id_fasilitas' => $fasilitas->id, // Menggunakan 'id' dari model Fasilitas
                        'nama_fasilitas' => $fasilitas->nama_fasilitas,
                        'gambar_ikon' => $fasilitas->gambar_ikon,
                        'full_gambar_url' => $fasilitas->gambar_ikon ? asset('assets/fasilitas/' . $fasilitas->gambar_ikon) : null,
                    ];
                }),
                'status_aktif' => (bool) $lapangan->status_aktif,
            ];
        });

        return response()->json($formattedLapangans);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Tambahkan aturan unique untuk nama_lapangan saat menyimpan baru
            'nama_lapangan' => 'required|string|max:255|unique:lapangans,nama_lapangan',
            'lokasi' => 'required|string|max:255',
            'harga_lapangan' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'deskripsi_lapangan' => 'nullable|string',
            'gambar_lapangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_aktif' => 'boolean',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id', // Memastikan ID fasilitas ada di tabel fasilitas
        ]);

        $gambarFileName = null;
        if ($request->hasFile('gambar_lapangan')) {
            $image = $request->file('gambar_lapangan');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/lapangan'), $gambarFileName);
        }

        $lastLapangan = Lapangan::orderBy('id_lapangan', 'desc')->first();
        // Pastikan Anda mem-parse id_lapangan dengan benar, misalnya 'LP001' -> 1
        $nextIdNumber = $lastLapangan ? (int) substr($lastLapangan->id_lapangan, 2) + 1 : 1;
        $idLapangan = 'LP' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

        $lapangan = Lapangan::create([
            'id_lapangan' => $idLapangan,
            'nama_lapangan' => $request->nama_lapangan,
            'lokasi' => $request->lokasi,
            'harga_lapangan' => $request->harga_lapangan ?? 0,
            'rating' => $request->rating ?? 0.0,
            'deskripsi_lapangan' => $request->deskripsi_lapangan,
            'gambar_lapangan' => $gambarFileName,
            'status_aktif' => $request->status_aktif ?? true,
        ]);

        if ($request->has('fasilitas')) {
            $lapangan->fasilitas()->sync($request->fasilitas);
        }

        return response()->json([
            'message' => 'Lapangan berhasil ditambahkan',
            'data' => $lapangan->load('fasilitas')->toArray()
        ], 201);
    }

    public function show($id)
    {
        // Gunakan where('id_lapangan', $id) karena primary key adalah id_lapangan
        $lapangan = Lapangan::with('fasilitas')->where('id_lapangan', $id)->first();

        if (!$lapangan) {
            return response()->json(['message' => 'Lapangan tidak ditemukan'], 404);
        }

        $formattedLapangan = [
            'id' => $lapangan->id_lapangan,
            'nama_lapangan' => $lapangan->nama_lapangan,
            'lokasi' => $lapangan->lokasi,
            'harga_lapangan' => $lapangan->harga_lapangan,
            'rating' => $lapangan->rating,
            'deskripsi_lapangan' => $lapangan->deskripsi_lapangan,
            'gambar_lapangan' => $lapangan->gambar_lapangan,
            'full_gambar_url' => $lapangan->gambar_lapangan ? asset('assets/lapangan/' . $lapangan->gambar_lapangan) : null,
            'fasilitas' => $lapangan->fasilitas->map(function ($fasilitas) {
                return [
                    'id_fasilitas' => $fasilitas->id,
                    'nama_fasilitas' => $fasilitas->nama_fasilitas,
                    'gambar_ikon' => $fasilitas->gambar_ikon,
                    'full_gambar_url' => $fasilitas->gambar_ikon ? asset('assets/fasilitas/' . $fasilitas->gambar_ikon) : null,
                ];
            }),
            'status_aktif' => (bool) $lapangan->status_aktif,
        ];

        return response()->json($formattedLapangan);
    }

    public function update(Request $request, $id)
    {
        // Temukan lapangan berdasarkan id_lapangan, bukan id
        $lapangan = Lapangan::where('id_lapangan', $id)->first();

        if (!$lapangan) {
            return response()->json(['message' => 'Lapangan tidak ditemukan'], 404);
        }

        $request->validate([
            // PERBAIKAN UTAMA: Tambahkan primary key column 'id_lapangan'
            'nama_lapangan' => 'required|string|max:255|unique:lapangans,nama_lapangan,' . $lapangan->id_lapangan . ',id_lapangan',
            'lokasi' => 'required|string|max:255',
            'harga_lapangan' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'deskripsi_lapangan' => 'nullable|string',
            'gambar_lapangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_aktif' => 'boolean',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'clear_gambar_lapangan' => 'nullable|boolean' // Tambahkan ini jika Anda akan mengirimkan flag untuk menghapus gambar
        ]);

        $gambarFileName = $lapangan->gambar_lapangan;
        if ($request->hasFile('gambar_lapangan')) {
            if ($gambarFileName && file_exists(public_path('assets/lapangan/' . $gambarFileName))) {
                unlink(public_path('assets/lapangan/' . $gambarFileName));
            }
            $image = $request->file('gambar_lapangan');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/lapangan'), $gambarFileName);
        } elseif ($request->boolean('clear_gambar_lapangan')) { // Jika flag clear_gambar_lapangan true
            if ($gambarFileName && file_exists(public_path('assets/lapangan/' . $gambarFileName))) {
                unlink(public_path('assets/lapangan/' . $gambarFileName));
            }
            $gambarFileName = null; // Set nama file menjadi null
        }


        $lapangan->update([
            'nama_lapangan' => $request->nama_lapangan,
            'lokasi' => $request->lokasi,
            'harga_lapangan' => $request->harga_lapangan ?? $lapangan->harga_lapangan,
            'rating' => $request->rating ?? $lapangan->rating,
            'deskripsi_lapangan' => $request->deskripsi_lapangan,
            'gambar_lapangan' => $gambarFileName,
            'status_aktif' => $request->status_aktif ?? $lapangan->status_aktif,
        ]);

        if ($request->has('fasilitas')) {
            $lapangan->fasilitas()->sync($request->fasilitas);
        } else {
            // Detach semua fasilitas jika tidak ada yang dipilih (misalnya semua checkbox tidak dicentang)
            $lapangan->fasilitas()->detach();
        }

        return response()->json([
            'message' => 'Lapangan berhasil diperbarui',
            'data' => $lapangan->load('fasilitas')->toArray()
        ]);
    }
    
    public function destroy($id)
    {
        // Gunakan where('id_lapangan', $id) karena primary key adalah id_lapangan
        $lapangan = Lapangan::where('id_lapangan', $id)->first();

        if (!$lapangan) {
            return response()->json(['message' => 'Lapangan tidak ditemukan'], 404);
        }

        if ($lapangan->gambar_lapangan && file_exists(public_path('assets/lapangan/' . $lapangan->gambar_lapangan))) {
            unlink(public_path('assets/lapangan/' . $lapangan->gambar_lapangan));
        }

        // Sebelum menghapus lapangan, lepaskan semua relasi fasilitasnya
        $lapangan->fasilitas()->detach(); 
        
        $lapangan->delete();

        return response()->json(['message' => 'Lapangan berhasil dihapus']);
    }
}