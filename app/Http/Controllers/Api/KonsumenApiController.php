<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konsumen; // Import model Konsumen
use Illuminate\Support\Facades\Hash; // Untuk hashing dan cek password
use Illuminate\Support\Facades\Validator; // Untuk validasi input API

class KonsumenApiController extends Controller
{
    /**
     * Store a newly created Konsumen in storage. (FUNGSI REGISTRASI)
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

    /**
     * Handle a login request for a Konsumen. (FUNGSI LOGIN BARU)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // 1. Validasi input email dan password
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Cari konsumen berdasarkan email
            $konsumen = Konsumen::where('email', $request->email)->first();

            // 3. Cek jika konsumen tidak ada ATAU password tidak cocok
            // Hash::check akan membandingkan password teks biasa dengan hash di database
            if (!$konsumen || !Hash::check($request->password, $konsumen->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials', // Pesan error yang umum untuk keamanan
                ], 401); // 401 Unauthorized
            }

            // 4. Jika berhasil, hapus token lama (jika ada) dan buat token baru
            // Ini untuk memastikan setiap login mendapat token yang fresh
            $konsumen->tokens()->delete();
            $token = $konsumen->createToken('api-token-for-'.$konsumen->email)->plainTextToken;

            // 5. Kirim respons sukses beserta token
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => $konsumen 
            ], 200); // 200 OK

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}