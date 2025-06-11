<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KonsumenApiController; // Import API Controller
use App\Http\Controllers\Api\LapanganController; // Import API Controller
use App\Http\Controllers\Api\FasilitasController; // Import API Controller
// use App\Http\Controllers\Api\PemesananController; // Import API Controller
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\Api\TopupApiController;


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

Route::get('/fasilitas', [FasilitasController::class, 'index']); // Mengambil semua fasilitas
Route::post('/fasilitas', [FasilitasController::class, 'store']); // Menambahkan fasilitas baru
Route::get('/fasilitas/{id}', [FasilitasController::class, 'show']); // Mengambil fasilitas berdasarkan ID
Route::put('/fasilitas/{id}', [FasilitasController::class, 'update']); // Memperbarui fasilitas
Route::delete('/fasilitas/{id}', [FasilitasController::class, 'destroy']); // Menghapus fasilitas


//Api Pemesanan
// Rute untuk MENGAMBIL data pemesanan (GET request)
Route::get('/pemesanan', [PemesananController::class, 'indexApi']); // Kita akan buat method indexApi
// Rute untuk MENYIMPAN data pemesanan (POST request)
Route::post('/pemesanan', [PemesananController::class, 'store']);

// Top UP
// Rute untuk Top Up dari Mobile App (Flutter)
Route::post('/topup', [TopupApiController::class, 'store']);

// Rute untuk konfirmasi Top Up dari Web Admin
Route::patch('/topup/{id}/confirm', [TopupApiController::class, 'confirm'])->middleware('auth:sanctum'); // Atau 'auth' kalo admin login pake session web
// Api Untuk uji coba top melalui postman
// Route::patch('/topup/{id}/confirm', [TopupApiController::class, 'confirm']); //->middleware('auth:sanctum'); // 
