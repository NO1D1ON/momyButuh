<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konsumen; // Import model Konsumen
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Support\Facades\Validator; // Untuk validasi input API

class KonsumenApiController extends Controller
{
    /**
     * Store a newly created Konsumen in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input dari aplikasi mobile
        $validator = Validator::make($request->all(), [
            'no_identitas' => 'required|string|unique:konsumens,no_identitas|max:255',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|unique:konsumens,email|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'required|string|min:6', // Password akan di-hash
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $konsumen = Konsumen::create([
                'no_identitas' => $request->no_identitas,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'password' => Hash::make($request->password), // HASH PASSWORD
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Konsumen registered successfully',
                'data' => $konsumen // Mengembalikan data konsumen yang baru dibuat
            ], 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register Konsumen',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    // Anda bisa menambahkan method lain seperti index, show, update, delete untuk API jika dibutuhkan
}