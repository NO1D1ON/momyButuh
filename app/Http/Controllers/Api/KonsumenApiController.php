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
        // Validasi HANYA input yang dikirim dari pengguna
        $validator = Validator::make($request->all(), [
            // 'no_identitas' DIHAPUS dari sini
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|unique:konsumens,email|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Ambil semua data yang sudah tervalidasi
            $validatedData = $validator->validated();

            // Hash password secara manual
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Gunakan $validatedData untuk membuat konsumen baru.
            // Kolom 'no_identitas' akan diisi secara otomatis oleh Model.
            $konsumen = Konsumen::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Konsumen registered successfully',
                'data' => $konsumen
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