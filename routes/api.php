<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KonsumenApiController; // Import API Controller
use App\Http\Controllers\Api\LapanganController; // Import API Controller
use App\Http\Controllers\Api\FasilitasController; // Import API Controller


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rute untuk mendaftarkan Konsumen baru dari aplikasi mobile
Route::post('/konsumen/register', [KonsumenApiController::class, 'store']);

// Api untuk lapangan
Route::get('/lapangans', [LapanganController::class, 'index']); // Untuk daftar lapangan (sudah ada)
Route::post('/lapangans', [LapanganController::class, 'store']); // Untuk tambah lapangan (sudah ada)
Route::get('/lapangans/{id}', [LapanganController::class, 'show']); // Untuk ambil data lapangan tunggal (BARU DITAMBAHKAN)
Route::put('/lapangans/{id}', [LapanganController::class, 'update']); // Untuk update lapangan (BARU DITAMBAHKAN)
Route::delete('/lapangans/{id}', [LapanganController::class, 'destroy']); // Untuk hapus lapangan (sudah ada)

Route::get('/fasilitas', [FasilitasController::class, 'index']);

