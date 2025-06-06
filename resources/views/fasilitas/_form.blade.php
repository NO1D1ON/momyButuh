{{-- resources/views/fasilitas/_form.blade.php --}}

<form id="fasilitasForm" enctype="multipart/form-data">
    <input type="hidden" id="fasilitasId">
    <div class="form-group">
        <label for="namaFasilitas">Nama Fasilitas:</label>
        {{-- Pastikan name atribut ada untuk pengiriman form data --}}
        <input type="text" id="namaFasilitas" name="nama_fasilitas" required>
    </div>
    <div class="form-group">
        <label for="gambarIkon">Gambar Ikon:</label>
        {{-- Pastikan name atribut ada untuk pengiriman form data --}}
        <input type="file" id="gambarIkon" name="gambar_ikon" accept="image/*">
        <p class="current-image-info" id="currentImageInfo"></p>
        <img id="previewImage" src="" alt="Preview Ikon" style="max-width: 100px; max-height: 100px; margin-top: 10px; display: none;">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>