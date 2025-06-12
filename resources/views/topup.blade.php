@extends('layouts.main')

@section('title', 'Top Up')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/topup.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Top Up</h1>
        <p>Halaman untuk mengelola permintaan Top Up dari konsumen.</p>
    </div>

    <div class="content-area">
        <div class="table-controls">
            <div class="search-box">
                <label for="search">Search:</label>
                <input type="text" id="search" placeholder="Cari berdasarkan nama...">
                <button type="button"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Top Up</th>
                        <th>No. Identitas</th>
                        <th>Nama Konsumen</th>
                        <th>No. Telepon</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topups as $topup)
                        <tr id="row-{{ $topup->id }}">
                            <td>{{ $topup->id }}</td>
                            {{-- Pake optional() biar aman kalo data konsumen ga ada --}}
                            <td>{{ optional($topup->konsumen)->no_identitas ?? 'N/A' }}</td>
                            <td>{{ optional($topup->konsumen)->nama ?? 'Konsumen Dihapus' }}</td>
                            <td>{{ optional($topup->konsumen)->no_telepon ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($topup->nominal, 0, ',', '.') }}</td>
                            <td>
                                {{-- Bikin span buat styling status --}}
                                <span class="status-badge status-{{ strtolower($topup->status) }}" id="status-{{ $topup->id }}">
                                    {{ $topup->status }}
                                </span>
                            </td>
                            <td>
                                {{-- Tombol konfirmasi cuma muncul kalo status masih Pending --}}
                                @if ($topup->status == 'Pending')
                                    <button class="btn btn-confirm" data-id="{{ $topup->id }}">
                                        <i class="fas fa-check"></i> Konfirmasi
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada data Top Up.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tampilkan link pagination --}}
        <div class="pagination-section">
            {{ $topups->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection


@push('scripts')
{{-- SweetAlert2 Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmButtons = document.querySelectorAll('.btn-confirm');

    confirmButtons.forEach(button => {
        // PERBAIKAN #1: Tambahkan parameter 'event' pada fungsi listener.
        button.addEventListener('click', function (event) {
            // PERBAIKAN #2: Panggil event.preventDefault() untuk mencegah aksi default browser.
            event.preventDefault(); 
            
            const topupId = this.dataset.id;

            Swal.fire({
                title: 'Konfirmasi Top Up?',
                text: `Anda yakin ingin mengkonfirmasi top up dengan ID: ${topupId}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });

                    fetch(`/topup/${topupId}/confirm`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                    })
                    .then(response => {
                        // PERBAIKAN #3: Penanganan error yang lebih baik.
                        // Coba parse JSON error dari server untuk pesan yang lebih spesifik.
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // PERBAIKAN #4: Ganti update manual dengan reload halaman.
                        // Ini lebih sederhana dan lebih andal untuk memastikan data konsisten.
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Top up telah berhasil dikonfirmasi.',
                            icon: 'success',
                            timer: 2000, // Popup akan hilang setelah 2 detik
                            showConfirmButton: false
                        }).then(() => {
                            // Muat ulang halaman untuk melihat data terbaru.
                            location.reload(); 
                        });
                    })
                    .catch(error => {
                        // Menangkap semua jenis error (network, server, dll)
                        // dan menampilkan pesan error dari server jika ada.
                        Swal.fire({
                            title: 'Oops... Terjadi Kesalahan',
                            // Gunakan pesan error dari server jika tersedia, jika tidak, gunakan pesan default.
                            text: error.message || 'Tidak dapat memproses permintaan Anda.',
                            icon: 'error'
                        });
                    });
                }
            });
        });
    });
});
</script>
@endpush