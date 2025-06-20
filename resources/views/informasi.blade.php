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
            <p>"Aplikasi ini kami menawarkan layanan jasa babysister yang dimana dirancang untuk mempermudah orangtua dalam mencari Babysistter untuk menjaga anaknya, 
                dimana didalam aplikasi yang kami rancang, untuk mempermudah dan meringankan tugas orang tua dalam menjaga anaknya."</p>
        </div>
    </div>
@endsection