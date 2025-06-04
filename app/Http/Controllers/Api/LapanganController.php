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
                        'id_fasilitas' => $fasilitas->id,
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
            'nama_lapangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_lapangan' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'deskripsi_lapangan' => 'nullable|string',
            'gambar_lapangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_aktif' => 'boolean',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
        ]);

        $gambarFileName = null;
        if ($request->hasFile('gambar_lapangan')) {
            $image = $request->file('gambar_lapangan');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension(); // Lebih aman dengan Str::random
            $image->move(public_path('assets/lapangan'), $gambarFileName);
        }

        $lastLapangan = Lapangan::orderBy('id_lapangan', 'desc')->first();
        $nextIdNumber = $lastLapangan ? (int) substr($lastLapangan->id_lapangan, 2) + 1 : 1;
        $idLapangan = 'LP' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

        $lapangan = Lapangan::create([
            'id_lapangan' => $idLapangan,
            'nama_lapangan' => $request->nama_lapangan,
            'lokasi' => $request->lokasi,
            'harga_lapangan' => $request->harga_lapangan ?? 0, // Default value
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
            'data' => $lapangan->load('fasilitas')->toArray() // Load relasi & konversi ke array
        ], 201);
    }

    public function show($id)
    {
        $lapangan = Lapangan::with('fasilitas')->find($id);

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

    public function update(Request $request, $id) // Pastikan parameter $id ada
    {
        $lapangan = Lapangan::find($id); // Laravel akan menemukan berdasarkan id_lapangan karena primaryKey

        if (!$lapangan) {
            return response()->json(['message' => 'Lapangan tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_lapangan' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'deskripsi_lapangan' => 'nullable|string',
            'gambar_lapangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_aktif' => 'boolean',
            'fasilitas' => 'nullable|array', // Pastikan ini 'fasilitas', bukan 'fasilitas_ids'
            'fasilitas.*' => 'exists:fasilitas,id', // Pastikan ini 'fasilitas.*'
        ]);

        $gambarFileName = $lapangan->gambar_lapangan;
        if ($request->hasFile('gambar_lapangan')) {
            if ($gambarFileName && file_exists(public_path('assets/lapangan/' . $gambarFileName))) {
                unlink(public_path('assets/lapangan/' . $gambarFileName));
            }
            $image = $request->file('gambar_lapangan');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/lapangan'), $gambarFileName);
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

        if ($request->has('fasilitas')) { // Pastikan ini 'fasilitas'
            $lapangan->fasilitas()->sync($request->fasilitas); // Pastikan ini 'fasilitas'
        } else {
            $lapangan->fasilitas()->detach();
        }

        return response()->json([
            'message' => 'Lapangan berhasil diperbarui',
            'data' => $lapangan->load('fasilitas')->toArray()
        ]);
    }
    
    public function destroy($id)
    {
        $lapangan = Lapangan::find($id);

        if (!$lapangan) {
            return response()->json(['message' => 'Lapangan tidak ditemukan'], 404);
        }

        if ($lapangan->gambar_lapangan && file_exists(public_path('assets/lapangan/' . $lapangan->gambar_lapangan))) {
            unlink(public_path('assets/lapangan/' . $lapangan->gambar_lapangan));
        }

        $lapangan->delete();

        return response()->json(['message' => 'Lapangan berhasil dihapus']);
    }
}