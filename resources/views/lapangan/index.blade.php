{{-- resources/views/lapangan/index.blade.php --}}

@extends('layouts.main')

@section('title', 'Data Lapangan')

@push('styles')
    <link rel="stylesheet" href="css/lapangan.css">
@endpush

@section('content')
    <div class="page-header">
        <h1>Lapangan</h1>
        <p>Data Lapangan</p>
    </div>

    <div class="content-area">
        {{-- Tombol Tambah dan Search Bar di atas tabel (sesuai gambar terakhir) --}}
        <div class="table-controls">
            <button class="btn btn-success btn-add-lapangan"><i class="fas fa-plus"></i> Lapangan</button>
            <div class="search-box">
                <label for="search-lapangan">Search:</label>
                <input type="text" id="search-lapangan" placeholder="Search...">
                <button type="button"><i class="fas fa-search"></i></button> {{-- Tombol Search terpisah --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lapangan</th>
                        <th>Lokasi</th>
                        <th>Harga Lapangan</th>
                        <th>Rating</th>
                        <th>Deskripsi Lapangan</th>
                        <th>Gambar Lapangan</th>
                        <th>Fasilitas Lapangan</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="lapanganTableBody">
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

    <div id="addLapanganModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 id="modalTitle">Tambah Lapangan Baru</h3>
            @include('lapangan._form') {{-- Memanggil partial view form --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/lapangan_script.js"></script>
@endpush