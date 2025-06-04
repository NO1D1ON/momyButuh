<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LapanganController extends Controller
{
    // Anda bisa menambahkan middleware 'auth' di constructor jika diperlukan
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('lapangan.index');
    }

    // Jika Anda ingin form tambah/edit terpisah, Anda bisa tambahkan method create/edit di sini
    // public function create() { return view('lapangan.create'); }
    // public function edit($id) { return view('lapangan.edit', compact('id')); }
}