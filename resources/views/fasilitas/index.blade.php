{{-- resources/views/fasilitas/index.blade.php --}}

@extends('layouts.main')

@section('title', 'Data Fasilitas')

@push('styles')
    <link rel="stylesheet" href="css/fasilitas.css">
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
            <form id="fasilitasForm" enctype="multipart/form-data">
                <input type="hidden" id="fasilitasId">
                <div class="form-group">
                    <label for="namaFasilitas">Nama Fasilitas:</label>
                    <input type="text" id="namaFasilitas" name="nama_fasilitas" required>
                </div>
                <div class="form-group">
                    <label for="gambarIkon">Gambar Ikon:</label>
                    <input type="file" id="gambarIkon" name="gambar_ikon" accept="image/*">
                    <p class="current-image-info" id="currentImageInfo"></p>
                    <img id="previewImage" src="" alt="Preview Ikon" style="max-width: 100px; max-height: 100px; margin-top: 10px; display: none;">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/fasilitas_script.js"></script>
@endpush