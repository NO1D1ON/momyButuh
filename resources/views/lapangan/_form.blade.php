{{-- resources/views/lapangan/_form.blade.php --}}

<form id="addLapanganForm" class="lapangan-form" enctype="multipart/form-data">
    @csrf {{-- Penting untuk keamanan Laravel --}}

    <div class="form-group">
        <label for="nama_lapangan">Nama Lapangan:</label>
        <input type="text" id="nama_lapangan" name="nama_lapangan" required>
    </div>

    <div class="form-group">
        <label for="lokasi">Lokasi:</label>
        <input type="text" id="lokasi" name="lokasi" required>
    </div>

    <div class="form-group">
        <label for="harga_lapangan">Harga Lapangan (Rp):</label>
        <input type="number" id="harga_lapangan" name="harga_lapangan" required min="0">
    </div>

    <div class="form-group">
        <label for="rating">Rating:</label>
        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5">
    </div>

    <div class="form-group">
        <label for="deskripsi_lapangan">Deskripsi Lapangan:</label>
        <textarea id="deskripsi_lapangan" name="deskripsi_lapangan" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label for="gambar_lapangan">Gambar Lapangan:</label>
        <input type="file" id="gambar_lapangan" name="gambar_lapangan" accept="image/*">
    </div>

    <div class="form-group">
        <label>Fasilitas Lapangan:</label>
        <div id="fasilitasContainer" class="fasilitas-checkboxes">
            {{-- Fasilitas akan dimuat di sini oleh JavaScript --}}
            {{-- Contoh statis jika belum ada JS untuk fasilitas: --}}
            {{-- <input type="checkbox" id="fasilitas_parkir" name="fasilitas[]" value="Parkir">
            <label for="fasilitas_parkir">Parkir</label><br>
            <input type="checkbox" id="fasilitas_toilet" name="fasilitas[]" value="Toilet">
            <label for="fasilitas_toilet">Toilet</label><br> --}}
        </div>
    </div>

    <div class="form-group">
        <label for="status_aktif">Status Aktif:</label>
        <select id="status_aktif" name="status_aktif">
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Lapangan</button>
    <button type="button" class="btn btn-secondary close-modal-button">Batal</button>
</form>