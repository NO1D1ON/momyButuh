@extends('layouts.main')

@section('title', 'Informasi Aplikasi')

@push('styles') {{-- Mendorong CSS spesifik halaman ke stack 'styles' --}}
    <link rel="stylesheet" href="/css/informasi.css">
@endpush

@section('content')
    <div class="dashboard-content">
        <div class="content-header">
            <h1>Informasi</h1>
            <p>Data Informasi</p> 
        </div>

        <div class="info-card">
            <p>"Aplikasi kami menawarkan layanan penyewaan lapangan futsal yang dirancang
                untuk mempermudah pengguna dalam menemukan dan memesan lapangan dengan
                mudah. Sebagai mitra terpercaya, kami berkomitmen untuk memberikan pengalaman
                yang cepat, efisien, dan user-friendly bagi komunitas penggemar olahraga futsal"</p>
        </div>
    </div>
@endsection