<?php
namespace App\Http\Controllers;

use App\Models\Topup; // <-- TAMBAHIN INI
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function index()
    {
        // Ambil semua data topup, sekalian data konsumennya (biar enteng)
        // Urutin dari yang paling baru
        $topups = Topup::with('konsumen')->latest()->paginate(10); // Paginate biar ga berat

        return view('topup', ['topups' => $topups]); // Kirim data ke view
    }
}