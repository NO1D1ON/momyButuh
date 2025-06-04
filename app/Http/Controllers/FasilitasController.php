<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    // Anda bisa menambahkan middleware 'auth' di constructor jika diperlukan
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('fasilitas.index');
    }
}