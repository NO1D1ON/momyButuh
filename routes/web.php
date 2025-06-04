<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\KonsumenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\FasilitasController;

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login'])->name('auth.login');
Route::get('forgot-password', [UserController::class, 'ShowPass'])->name('ShowPass');

// Harus login dulu baru kebuka
Route::middleware(['auth'])->group(function() {

Route::get('dashboard', [UserController::class, 'showHome'])->name('dashboard');
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::get('informasi', [UserController::class, 'showInformasi'])->name('informasi');
Route::get('pemesanan', [UserController::class, 'showPemesanan'])->name('pemesanan');
Route::get('pembayaran', [UserController::class, 'showPembayaran'])->name('pembayaran');
Route::get('konsumen', [KonsumenController::class, 'index'])->name('konsumen');

// Pastikan ini memanggil LapanganController::index yang mengembalikan view lapangan/index.blade.php
Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');

// Rute untuk halaman Data Fasilitas
Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');

});