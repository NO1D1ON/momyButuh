<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topup;
use App\Models\Konsumen;
use Illuminate\Support\Facades\Validator;
// Jangan lupa import DB facade
use Illuminate\Support\Facades\DB;
use Throwable;

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
    // public function confirm(Request $request, $id)
    // {
    //     $topup = Topup::find($id);

    //     if (!$topup) {
    //         return response()->json(['message' => 'Data top up tidak ditemukan'], 404);
    //     }
        
    //     // Cari konsumen buat nambahin saldonya
    //     $konsumen = Konsumen::find($topup->konsumen_id);
    //     if ($konsumen) {
    //         // Asumsikan ada kolom 'saldo' di tabel 'konsumens'
    //         // Kalo belum ada, tambahin dulu kolomnya pake migration.
    //         // $konsumen->saldo += $topup->nominal;
    //         // $konsumen->save();
    //     }
        
    //     // Update status topup jadi Berhasil
    //     $topup->status = 'Berhasil';
    //     $topup->save();
        
    //     // Ambil data konsumen buat dikirim balik
    //     $konsumenData = $topup->konsumen;

    //     return response()->json([
    //         'message' => 'Top up berhasil dikonfirmasi!',
    //         'data' => [
    //             'id' => $topup->id,
    //             'status' => $topup->status,
    //             'konsumen' => [
    //                 'no_identitas' => $konsumenData->no_identitas,
    //                 'nama' => $konsumenData->nama,
    //                 'no_telepon' => $konsumenData->no_telepon
    //             ],
    //             'nominal' => $topup->nominal
    //         ]
    //     ], 200);
    // }
    public function confirm(Request $request, $id)
    {
        $topup = Topup::with('konsumen')->find($id);
        // $topup = Topup::with('konsumen')->where('kode', $id)->first();


        if (!$topup) {
            return response()->json(['message' => 'Data top up tidak ditemukan'], 404);
        }

        if ($topup->status == 'Berhasil') {
            return response()->json(['message' => 'Top up ini sudah pernah dikonfirmasi.'], 400);
        }

        try {
            // Gunakan DB Transaction untuk keamanan data
            // Kalo salah satu gagal, semua proses bakal dibatalin (rollback)
            DB::transaction(function () use ($topup) {
                // 1. Ambil data konsumen dari relasi
                $konsumen = $topup->konsumen;
                
                if ($konsumen) {
                    // 2. Tambahkan nominal top up ke saldo konsumen
                    $konsumen->saldo += $topup->nominal;
                    $konsumen->save();
                }

                // 3. Ubah status top up jadi Berhasil
                $topup->status = 'Berhasil';
                $topup->save();
            });

        } catch (Throwable $e) {
            // Kalo ada error di tengah jalan, kasih respons gagal
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses top up.',
                'error' => $e->getMessage()
            ], 500);
        }
        
        // Refresh data topup untuk dapet data konsumen terbaru (termasuk saldo)
        $topup->refresh();

        return response()->json([
            'message' => 'Top up berhasil dikonfirmasi dan saldo telah ditambahkan!',
            'data' => $topup
        ], 200);
    }
}