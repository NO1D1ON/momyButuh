<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage; // Tidak digunakan, bisa dihapus jika tidak ada kebutuhan lain
use Illuminate\Support\Str;

class FasilitasController extends Controller
{
    /**
     * Menampilkan daftar semua fasilitas.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $fasilitas = Fasilitas::all();

        // Memformat data fasilitas untuk menyertakan full URL gambar ikon
        $formattedFasilitas = $fasilitas->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_fasilitas' => $item->nama_fasilitas,
                'gambar_ikon' => $item->gambar_ikon,
                // Pastikan 'assets/fasilitas/' adalah path yang benar di folder public
                'full_gambar_url' => $item->gambar_ikon ? asset('assets/fasilitas/' . $item->gambar_ikon) : null,
            ];
        });

        return response()->json($formattedFasilitas);
    }

    /**
     * Menyimpan fasilitas baru.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas',
            'gambar_ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
        ]);

        $gambarFileName = null;
        if ($request->hasFile('gambar_ikon')) {
            $image = $request->file('gambar_ikon');
            // Menghasilkan nama file unik berdasarkan timestamp dan string acak
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // Memindahkan gambar ke folder public/assets/fasilitas
            $image->move(public_path('assets/fasilitas'), $gambarFileName);
        }

        // Membuat entri fasilitas baru di database
        $fasilitas = Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'gambar_ikon' => $gambarFileName,
        ]);

        return response()->json([
            'message' => 'Fasilitas berhasil ditambahkan',
            'data' => [
                'id' => $fasilitas->id,
                'nama_fasilitas' => $fasilitas->nama_fasilitas,
                'gambar_ikon' => $fasilitas->gambar_ikon,
                'full_gambar_url' => $fasilitas->gambar_ikon ? asset('assets/fasilitas/' . $fasilitas->gambar_ikon) : null,
            ]
        ], 201); // Kode status 201 untuk 'Created'
    }

    /**
     * Menampilkan detail fasilitas tertentu.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return response()->json(['message' => 'Fasilitas tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $fasilitas->id,
            'nama_fasilitas' => $fasilitas->nama_fasilitas,
            'gambar_ikon' => $fasilitas->gambar_ikon,
            'full_gambar_url' => $fasilitas->gambar_ikon ? asset('assets/fasilitas/' . $fasilitas->gambar_ikon) : null,
        ]);
    }

    /**
     * Memperbarui fasilitas tertentu.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return response()->json(['message' => 'Fasilitas tidak ditemukan'], 404);
        }

        $request->validate([
            // Unique rule dikecualikan untuk ID fasilitas saat ini
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas,' . $id,
            'gambar_ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'clear_gambar_ikon' ditambahkan sebagai validasi boolean opsional
            'clear_gambar_ikon' => 'nullable|boolean'
        ]);

        $gambarFileName = $fasilitas->gambar_ikon; // Pertahankan nama file yang ada secara default

        // Jika ada file gambar baru diupload
        if ($request->hasFile('gambar_ikon')) {
            // Hapus gambar lama jika ada
            if ($gambarFileName && file_exists(public_path('assets/fasilitas/' . $gambarFileName))) {
                unlink(public_path('assets/fasilitas/' . $gambarFileName));
            }
            $image = $request->file('gambar_ikon');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/fasilitas'), $gambarFileName);
        } elseif ($request->boolean('clear_gambar_ikon')) { // Jika flag clear_gambar_ikon true
            // Hapus gambar lama jika ada
            if ($gambarFileName && file_exists(public_path('assets/fasilitas/' . $gambarFileName))) {
                unlink(public_path('assets/fasilitas/' . $gambarFileName));
            }
            $gambarFileName = null; // Set nama file menjadi null
        }

        // Perbarui data fasilitas di database
        $fasilitas->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'gambar_ikon' => $gambarFileName,
        ]);

        return response()->json([
            'message' => 'Fasilitas berhasil diperbarui',
            'data' => [
                'id' => $fasilitas->id,
                'nama_fasilitas' => $fasilitas->nama_fasilitas,
                'gambar_ikon' => $fasilitas->gambar_ikon,
                'full_gambar_url' => $fasilitas->gambar_ikon ? asset('assets/fasilitas/' . $fasilitas->gambar_ikon) : null,
            ]
        ]);
    }

    /**
     * Menghapus fasilitas tertentu.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return response()->json(['message' => 'Fasilitas tidak ditemukan'], 404);
        }

        // Hapus file gambar ikon dari penyimpanan publik jika ada
        if ($fasilitas->gambar_ikon && file_exists(public_path('assets/fasilitas/' . $fasilitas->gambar_ikon))) {
            unlink(public_path('assets/fasilitas/' . $fasilitas->gambar_ikon));
        }

        // Hapus entri fasilitas dari database
        $fasilitas->delete();

        return response()->json(['message' => 'Fasilitas berhasil dihapus']);
    }
}