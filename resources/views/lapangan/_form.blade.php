{{-- resources/views/lapangan/_form.blade.php --}}

<form id="addLapanganForm" class="lapangan-form" enctype="multipart/form-data">
    @csrf {{-- Penting untuk keamanan Laravel --}}

    <div class="form-group">
        <label for="nama_Babysitter">Nama Babysitter:</label>
        <input type="text" id="nama_Babysitter" name="nama_Babysitter" required>
    </div>

    <div class="form-group">
        <label for="lokasi">Lokasi:</label>
        <input type="text" id="lokasi" name="lokasi" required>
    </div>

    <div class="form-group">
        <label for="harga_Per_Jam">Harga Per Jam (Rp):</label>
        <input type="number" id="harga_Per_Jam" name="harga_Per_Jam" required min="0">
    </div>

    <div class="form-group">
        <label for="rating">Rating:</label>
        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5">
    </div>

    <div class="form-group">
        <label for="deskripsi_Babysitter">Deskripsi Babysitter:</label>
        <textarea id="deskripsi_Babysitter" name="deskripsi_Babysitter" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label for="foto_Babysitter">Gambar Lapangan:</label>
        <input type="file" id="foto_Babysitter" name="foto_Babysitter" accept="image/*">
    </div>

    <div class="form-group">
        <label>Fasilitas Babysitter:</label>
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

    <button type="submit" class="btn btn-primary">Simpan Babysitter</button>
    <button type="button" class="btn btn-secondary close-modal-button">Batal</button>
</form>