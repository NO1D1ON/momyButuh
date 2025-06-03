<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login'])->name('auth.login');
Route::get('forgot-password', [UserController::class, 'ShowPass'])->name('ShowPass');

// Harus login dulu baru kebuka
Route::middleware(['auth'])->group(function() {

Route::get('dashboard', [UserController::class, 'showHome'])->name('dashboard');
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::get('informasi', [UserController::class, 'showInformasi'])->name('informasi');
Route::get('fasilitas', [UserController::class, 'showFasilitas'])->name('fasilitas');
Route::get('konsumen', [UserController::class, 'showKonsumen'])->name('konsumen');
Route::get('pemesanan', [UserController::class, 'showPemesanan'])->name('pemesanan');
Route::get('pembayaran', [UserController::class, 'showPembayaran'])->name('pembayaran');
Route::get('lapangan', [UserController::class, 'showLapangan'])->name('lapangan');


});