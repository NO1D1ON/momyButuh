<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KonsumenApiController; // Import API Controller

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rute untuk mendaftarkan Konsumen baru dari aplikasi mobile
Route::post('/konsumen/register', [KonsumenApiController::class, 'store']);