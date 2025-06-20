{{-- resources/views/lapangan/index.blade.php --}}

@extends('layouts.main')

@section('title', 'Data Babysitter')

@push('styles')
    {{-- Gaya CSS lainnya --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Tambahkan CSS SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@push('styles')
    <link rel="stylesheet" href="css/lapangan.css">
@endpush

@section('content')
    <div class="page-header">
        <h1>Babysistter</h1>
        <p>Data Babysistter</p>
    </div>

    <div class="content-area">
        {{-- Tombol Tambah dan Search Bar di atas tabel (sesuai gambar terakhir) --}}
        <div class="table-controls">
            <button class="btn btn-success btn-add-lapangan"><i class="fas fa-plus"></i> Babysitter</button>
            <!-- <div class="search-box">
                <label for="search-lapangan">Search:</label>
                <input type="text" id="search-lapangan" placeholder="Search...">
                <button type="button"><i class="fas fa-search"></i></button> {{-- Tombol Search terpisah --}}
            </div> -->
            {{-- Bagian Pencarian --}}
            <div class="search-box">
                <form action="{{ route('Babysitter.index') }}" method="GET">
                    <label for="search-Babysitter">Search :</label>
                    <input type="text" id="search-Babysitter" name="search" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Babysistter</th>
                        <th>Alamat Babysistter</th>
                        <th>Harga Per Jam</th>
                        <th>Rating</th>
                        <th>Deskripsi Babysistter</th>
                        <th>Foto Babysistter</th>
                        <th>Fasilitas Babysistter</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <!-- <tbody id="lapanganTableBody">
                    {{-- Data akan dimasukkan di sini oleh JavaScript --}}
                </tbody> -->
                 <tbody id="lapanganTableBody">
                   {{-- PENTING: ISI BAGIAN INI DENGAN LOOP BLADE, JANGAN JS --}}
                    @forelse ($Babysitter as $Babysitter)
                        <tr>
                            <td>{{ $Babysitter->id_Babysitter }}</td> {{-- Pastikan nama kolom sesuai model --}}
                            <td>{{ $Babysitter->nama_Babysitter }}</td>
                            <td>{{ $Babysitter->alamat_Babysitter }}</td>
                            <td>{{ number_format($Babysitter->Harga_Per_Jam, 0, ',', '.') }}</td>
                            <td>{{ $Babysitter->rating }}</td>
                            <td>{{ Str::limit($Babysitter->deskripsi_Babysitter, 50) }}</td>
                            <td>
                                @if($Babysitter->Foto_Babysitter)
                                    <img src="{{ asset('storage/' . $Babysitter->Foto_Babysitter) }}" alt="Gambar Lapangan" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    Tidak ada gambar
                                @endif
                            </td>
                            <td>
                                @forelse($lapangan->fasilitas as $fasilitas)
                                    <span class="badge badge-info">{{ $fasilitas->nama_fasilitas }}</span><br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>
                                @if($lapangan->status_aktif)
                                    <i class="fas fa-check-circle status-active"></i> Aktif
                                @else
                                    <i class="fas fa-times-circle status-inactive"></i> Tidak Aktif
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-btn" data-id="{{ $lapangan->id_lapangan }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $lapangan->id_lapangan }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center;">Tidak ada data lapangan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- <div class="pagination-section">
            <nav class="pagination">
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div> -->
    </div>

    <!-- <div id="addLapanganModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 id="modalTitle">Tambah Lapangan Baru</h3>
            @include('lapangan._form') {{-- Memanggil partial view form --}}
        </div>
    </div> -->
     <div id="addLapanganModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 id="modalTitle">Tambah Lapangan Baru</h3> {{-- Pastikan h3 memiliki ID modalTitle --}}
            {{-- Form Anda --}}
            @include('lapangan._form')
            <button class="btn btn-secondary close-modal-button">Batal</button>
        </div>
    </div>

    <div class="pagination-section">
        {{ $lapangans->links() }} {{-- Pastikan ini memanggil paginasi --}}
    </div>
@endsection

@push('scripts')
    {{-- Tambahkan JS SweetAlert2 (sebelum script Anda sendiri) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/lapangan_script.js"></script>
@endpush