<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::all();

        $formattedFasilitas = $fasilitas->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_fasilitas' => $item->nama_fasilitas,
                'gambar_ikon' => $item->gambar_ikon,
                'full_gambar_url' => $item->gambar_ikon ? asset('assets/fasilitas/' . $item->gambar_ikon) : null,
            ];
        });

        return response()->json($formattedFasilitas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas',
            'gambar_ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gambarFileName = null;
        if ($request->hasFile('gambar_ikon')) {
            $image = $request->file('gambar_ikon');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/fasilitas'), $gambarFileName);
        }

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
        ], 201);
    }

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

    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return response()->json(['message' => 'Fasilitas tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas,' . $id,
            'gambar_ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'clear_gambar_ikon' => 'boolean' // Opsional: Tambahkan ini jika ingin flag untuk menghapus gambar tanpa mengganti
        ]);

        $gambarFileName = $fasilitas->gambar_ikon;
        if ($request->hasFile('gambar_ikon')) {
            if ($gambarFileName && file_exists(public_path('assets/fasilitas/' . $gambarFileName))) {
                unlink(public_path('assets/fasilitas/' . $gambarFileName));
            }
            $image = $request->file('gambar_ikon');
            $gambarFileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/fasilitas'), $gambarFileName);
        } elseif ($request->boolean('clear_gambar_ikon')) { // Contoh penggunaan clear_gambar_ikon
            if ($gambarFileName && file_exists(public_path('assets/fasilitas/' . $gambarFileName))) {
                unlink(public_path('assets/fasilitas/' . $gambarFileName));
            }
            $gambarFileName = null;
        }

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

    public function destroy($id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return response()->json(['message' => 'Fasilitas tidak ditemukan'], 404);
        }

        if ($fasilitas->gambar_ikon && file_exists(public_path('assets/fasilitas/' . $fasilitas->gambar_ikon))) {
            unlink(public_path('assets/fasilitas/' . $fasilitas->gambar_ikon));
        }

        $fasilitas->delete();

        return response()->json(['message' => 'Fasilitas berhasil dihapus']);
    }
}