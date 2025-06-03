<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konsumen; // Import model Konsumen

class KonsumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Konsumen::query();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_identitas', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('no_telepon', 'like', '%' . $search . '%');
            });
        }

        // Paginasi: Ambil 10 data per halaman (sesuaikan jika perlu)
        $konsumens = $query->paginate(10);

        return view('konsumen', compact('konsumens'));
    }
}