@extends('layouts.main') {{-- Menggunakan layout utama --}}

@section('title', 'Dashboard Admin') {{-- Judul spesifik halaman --}}

@section('content') {{-- Konten untuk bagian 'content' di layout --}}
    <div class="dashboard-content">
        <div class="center-content">
            <div class="main-logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo FS">
            </div>
            <p class="welcome-text">Welcome Admin {{ Auth::user()->name }} </p>
        </div>
    </div>
@endsection