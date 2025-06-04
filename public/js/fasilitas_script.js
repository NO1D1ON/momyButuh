// public/js/fasilitas_script.js

document.addEventListener('DOMContentLoaded', () => {
    const fasilitasTableBody = document.getElementById('fasilitasTableBody');
    const modal = document.getElementById('fasilitasModal');
    const modalTitle = document.getElementById('modalTitle');
    const closeButton = document.querySelector('.close-button');
    const fasilitasForm = document.getElementById('fasilitasForm');
    const fasilitasIdInput = document.getElementById('fasilitasId');
    const namaFasilitasInput = document.getElementById('namaFasilitas');
    const gambarIkonInput = document.getElementById('gambarIkon');
    const currentImageInfo = document.getElementById('currentImageInfo');
    const previewImage = document.getElementById('previewImage');
    const addFasilitasBtn = document.querySelector('.btn-add-fasilitas');
    const searchInput = document.getElementById('search-fasilitas');
    const searchButton = searchInput.nextElementSibling; // Tombol search setelah input

    let isEditMode = false;

    function openModal() {
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
        fasilitasForm.reset();
        fasilitasIdInput.value = '';
        previewImage.style.display = 'none';
        previewImage.src = '';
        currentImageInfo.textContent = '';
        isEditMode = false;
        gambarIkonInput.value = ''; // Pastikan input file dikosongkan
    }

    addFasilitasBtn.addEventListener('click', () => {
        modalTitle.textContent = 'Tambah Fasilitas Baru';
        openModal();
    });

    closeButton.addEventListener('click', closeModal);

    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            closeModal();
        }
    });

    gambarIkonInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
            previewImage.src = '';
        }
    });

    function renderTableData(data) {
        fasilitasTableBody.innerHTML = '';

        if (data.length === 0) {
            fasilitasTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Tidak ada data fasilitas.</td></tr>'; // colspan 3 karena 3 kolom
            return;
        }

        data.forEach(fasilitas => {
            const row = document.createElement('tr');

            const tdId = document.createElement('td');
            tdId.textContent = `FL${String(fasilitas.id).padStart(2, '0')}`;
            row.appendChild(tdId);

            const tdGambar = document.createElement('td');
            if (fasilitas.gambar_ikon && fasilitas.full_gambar_url) {
                const img = document.createElement('img');
                img.src = fasilitas.full_gambar_url;
                img.alt = `Icon ${fasilitas.nama_fasilitas}`;
                img.classList.add('img-icon-small');
                tdGambar.appendChild(img);
                // Tambahkan nama file di samping gambar seperti di gambar yang Anda berikan
                const spanFileName = document.createElement('span');
                spanFileName.textContent = ` ${fasilitas.gambar_ikon}`;
                tdGambar.appendChild(spanFileName);
            } else {
                tdGambar.textContent = 'Tidak ada ikon';
            }
            row.appendChild(tdGambar);

            const tdAksi = document.createElement('td');
            const editBtn = document.createElement('a');
            editBtn.href = "#";
            editBtn.classList.add('btn-action', 'edit');
            editBtn.innerHTML = '<i class="fas fa-edit"></i>';
            editBtn.title = `Edit ${fasilitas.nama_fasilitas}`;
            editBtn.addEventListener('click', (e) => {
                e.preventDefault();
                isEditMode = true;
                modalTitle.textContent = `Edit Fasilitas: ${fasilitas.nama_fasilitas}`;
                fasilitasIdInput.value = fasilitas.id;
                namaFasilitasInput.value = fasilitas.nama_fasilitas; // Nama fasilitas tetap diisi di form

                gambarIkonInput.value = '';

                if (fasilitas.gambar_ikon && fasilitas.full_gambar_url) {
                    currentImageInfo.textContent = `Current: ${fasilitas.gambar_ikon}`;
                    previewImage.src = fasilitas.full_gambar_url;
                    previewImage.style.display = 'block';
                } else {
                    currentImageInfo.textContent = 'Tidak ada ikon saat ini.';
                    previewImage.style.display = 'none';
                    previewImage.src = '';
                }
                openModal();
            });

            const deleteBtn = document.createElement('a');
            deleteBtn.href = "#";
            deleteBtn.classList.add('btn-action', 'delete');
            deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            deleteBtn.title = `Hapus ${fasilitas.nama_fasilitas}`;
            deleteBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                if (confirm(`Apakah Anda yakin ingin menghapus fasilitas "${fasilitas.nama_fasilitas}"?`)) {
                    try {
                        const response = await fetch(`/api/fasilitas/${fasilitas.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }

                        alert('Fasilitas berhasil dihapus!');
                        fetchDataFasilitas();
                    } catch (error) {
                        console.error('Gagal menghapus fasilitas:', error);
                        alert(`Gagal menghapus fasilitas: ${error.message}`);
                    }
                }
            });

            tdAksi.appendChild(editBtn);
            tdAksi.appendChild(deleteBtn);
            row.appendChild(tdAksi);

            fasilitasTableBody.appendChild(row);
        });
    }

    async function fetchDataFasilitas() {
        try {
            const response = await fetch('/api/fasilitas');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            renderTableData(data);
        } catch (error) {
            console.error("Gagal mengambil data fasilitas:", error);
            fasilitasTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red;">Gagal memuat data. Silakan coba lagi.</td></tr>'; // colspan 3
        }
    }

    fasilitasForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = fasilitasIdInput.value;
        const url = id ? `/api/fasilitas/${id}` : '/api/fasilitas';
        const method = 'POST';

        const formData = new FormData();
        formData.append('nama_fasilitas', namaFasilitasInput.value);
        if (gambarIkonInput.files.length > 0) {
            formData.append('gambar_ikon', gambarIkonInput.files[0]);
        }

        if (id) {
            formData.append('_method', 'PUT');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                let errorMessage = errorData.message || `HTTP error! status: ${response.status}`;
                if (errorData.errors) {
                    errorMessage += "\n" + Object.values(errorData.errors).flat().join("\n");
                }
                throw new Error(errorMessage);
            }

            const result = await response.json();
            alert(result.message);
            closeModal();
            fetchDataFasilitas();
        } catch (error) {
            console.error('Gagal menyimpan fasilitas:', error);
            alert(`Gagal menyimpan fasilitas: ${error.message}`);
        }
    });

    fetchDataFasilitas();

    // Event listener untuk tombol search (jika ingin aksi saat tombol diklik)
    if (searchButton && searchButton.tagName === 'BUTTON') { // Pastikan elemen adalah button
        searchButton.addEventListener('click', () => {
            const searchText = searchInput.value.toLowerCase();
            console.log("Searching for:", searchText);
            // Anda bisa memanggil fetchDataFasilitas() dengan parameter search di sini
        });
    }

    // Event listener untuk input search (jika ingin aksi saat mengetik)
    searchInput.addEventListener('keyup', (event) => {
        if (event.key === 'Enter') { // Aksi saat menekan Enter
            const searchText = event.target.value.toLowerCase();
            console.log("Searching for:", searchText);
            // Anda bisa memanggil fetchDataFasilitas() dengan parameter search di sini
        }
    });
});