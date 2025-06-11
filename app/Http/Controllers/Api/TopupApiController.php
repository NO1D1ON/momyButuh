<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topup;
use App\Models\Konsumen;
use Illuminate\Support\Facades\Validator;

class TopupApiController extends Controller
{
    /**
     * Endpoint untuk nerima request top up dari Flutter.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari Flutter
        $validator = Validator::make($request->all(), [
            'no_identitas' => 'required|string|exists:konsumens,no_identitas', // Pastikan konsumen ada
            'nominal'      => 'required|numeric|min:10000', // Minimal topup 10rb
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat record topup baru
        $topup = Topup::create([
            'konsumen_id' => $request->no_identitas,
            'nominal'     => $request->nominal,
            'status'      => 'Pending', // Status awal
        ]);

        return response()->json([
            'message' => 'Request top up berhasil, menunggu konfirmasi admin.',
            'data'    => $topup
        ], 201);
    }

    /**
     * Endpoint untuk konfirmasi pembayaran oleh Admin dari web.
     */
    public function confirm(Request $request, $id)
    {
        $topup = Topup::find($id);

        if (!$topup) {
            return response()->json(['message' => 'Data top up tidak ditemukan'], 404);
        }
        
        // Cari konsumen buat nambahin saldonya
        $konsumen = Konsumen::find($topup->konsumen_id);
        if ($konsumen) {
            // Asumsikan ada kolom 'saldo' di tabel 'konsumens'
            // Kalo belum ada, tambahin dulu kolomnya pake migration.
            // $konsumen->saldo += $topup->nominal;
            // $konsumen->save();
        }
        
        // Update status topup jadi Berhasil
        $topup->status = 'Berhasil';
        $topup->save();
        
        // Ambil data konsumen buat dikirim balik
        $konsumenData = $topup->konsumen;

        return response()->json([
            'message' => 'Top up berhasil dikonfirmasi!',
            'data' => [
                'id' => $topup->id,
                'status' => $topup->status,
                'konsumen' => [
                    'no_identitas' => $konsumenData->no_identitas,
                    'nama' => $konsumenData->nama,
                    'no_telepon' => $konsumenData->no_telepon
                ],
                'nominal' => $topup->nominal
            ]
        ], 200);
    }
}