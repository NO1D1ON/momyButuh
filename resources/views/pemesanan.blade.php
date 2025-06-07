{{-- resources/views/pemesanan.blade.php --}}

@extends('layouts.main')

@section('title', 'Data Pemesanan')

@push('styles')
    <link rel="stylesheet" href="css/pemesanan.css"> {{-- Gunakan asset() --}}
@endpush

@section('content')
    <div class="page-header">
        <h1>Pemesanan</h1>
        <p>Data Pemesanan</p>
    </div>

    <div class="content-area">
        {{-- Tombol Tambah dan Search Bar di atas tabel --}}
        <div class="table-controls">
            <div class="search-box">
                <label for="search-lapangan">Search:</label>
                <input type="text" id="search-lapangan" placeholder="Search...">
                <button type="button"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Pemesanan</th>
                        <th>ID User</th>
                        <th>ID Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Harga</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemesanan as $item)
                    <tr>
                        <td>{{ $item->id_pemesanan }}</td>
                        <td>{{ $item->konsumen_id }}</td>
                        <td>{{ $item->lapangan_id }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->jam_mulai }}</td>
                        <td>{{ $item->jam_selesai }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data pemesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-section">
            {{ $pemesanan->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/pemesanan.js"></script> {{-- Gunakan asset() --}}
@endpush