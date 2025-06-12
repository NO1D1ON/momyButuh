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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmButtons = document.querySelectorAll('.btn-confirm');

    confirmButtons.forEach(button => {
        button.addEventListener('click', function () {
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
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });

                    // INI BAGIAN YANG DIPERBAIKI (URL-NYA TANPA /api)
                    fetch(`/topup/${topupId}/confirm`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const statusBadge = document.getElementById(`status-${topupId}`);
                        if (statusBadge) {
                            statusBadge.textContent = data.data.status;
                            statusBadge.classList.remove('status-pending');
                            statusBadge.classList.add('status-berhasil');
                        }
                        // 'this' di dalam .then bisa beda, jadi kita cari lagi tombolnya
                        document.querySelector(`.btn-confirm[data-id="${topupId}"]`).remove();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: `Top up ${topupId} telah dikonfirmasi.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengkonfirmasi top up!',
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