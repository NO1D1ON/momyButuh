{{-- resources/views/admin/fasilitas/_form.blade.php --}}

{{-- Form untuk Tambah/Edit Fasilitas --}}
<h3 class="modal-title">{{ $action == 'create' ? 'Tambah Fasilitas Lapangan' : 'Edit Fasilitas Lapangan' }}</h3>

<form id="fasilitasForm" action="{{ $action == 'create' ? route('fasilitas.store') : route('fasilitas.update', $fasilitasLapangan->id ?? '') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($action == 'edit')
        @method('PUT') {{-- Untuk metode update --}}
    @endif

    <div class="form-group">
        <label for="nama">Nama Fasilitas:</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $fasilitasLapangan->nama ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="ikon">Gambar Ikon:</label>
        @if ($action == 'edit' && ($fasilitasLapangan->ikon_path ?? false))
            <img src="{{ asset($fasilitasLapangan->ikon_path) }}" alt="Ikon Saat Ini" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 10px; display: block;">
            <small>Ikon saat ini. Kosongkan jika tidak diubah.</small>
        @else
            <small>Upload gambar ikon (PNG, JPG, SVG) maks 2MB.</small>
        @endif
        <input type="file" id="ikon" name="ikon" accept="image/png, image/jpeg, image/svg+xml" {{ $action == 'create' ? 'required' : '' }}>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ $action == 'create' ? 'Simpan' : 'Update' }}</button>
        <button type="button" class="btn btn-secondary close-modal-btn">Batal</button> {{-- Tombol Batal --}}
    </div>
</form>