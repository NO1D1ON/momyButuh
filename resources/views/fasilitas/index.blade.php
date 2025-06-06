{{-- resources/views/fasilitas/index.blade.php --}}

@extends('layouts.main')

@section('title', 'Data Fasilitas')

@push('styles')
    <link rel="stylesheet" href="css/fasilitas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Tambahkan CSS SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="page-header">
        <h1>Fasilitas Lapangan</h1>
        <p>Data Fasilitas Lapangan</p>
    </div>

    <div class="content-area">
        {{-- Tombol Tambah dan Search Bar di atas tabel (sesuai gambar terbaru) --}}
        <div class="table-actions">
            <button class="btn btn-success btn-add-fasilitas"><i class="fas fa-plus"></i> Tambah Data</button>
            <div class="search-box">
                <label for="search-fasilitas">Search:</label>
                <input type="text" id="search-fasilitas" placeholder="Search...">
                <button type="button"><i class="fas fa-search"></i></button> {{-- Tombol Search terpisah --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Fasilitas</th> {{-- Tambahkan kolom ini --}}
                        <th>Gambar Ikon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="fasilitasTableBody">
                    {{-- Data akan dimasukkan di sini oleh JavaScript --}}
                </tbody>
            </table>
        </div>

        <div class="pagination-section">
            <nav class="pagination">
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div id="fasilitasModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="modalTitle">Tambah Fasilitas Baru</h2>
            {{-- Sertakan file _form.blade.php di sini --}}
            @include('fasilitas._form')
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Tambahkan JS SweetAlert2 (sebelum script Anda sendiri) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/fasilitas_script.js"></script>
@endpush