// public/js/lapangan_script.js

document.addEventListener('DOMContentLoaded', () => {
    const dataTableBody = document.getElementById('lapanganTableBody');
    const searchInput = document.getElementById('search-lapangan');
    const searchButton = searchInput.nextElementSibling;

    // Elemen modal dan tombol terkait
    const addLapanganBtn = document.querySelector('.btn-add-lapangan');
    const addLapanganModal = document.getElementById('addLapanganModal');
    const closeModalButtons = document.querySelectorAll('.close-button, .close-modal-button'); // Pilih kedua tombol close

    const addLapanganForm = document.getElementById('addLapanganForm');
    const fasilitasContainer = document.getElementById('fasilitasContainer');

    let editingLapanganId = null;


    // Fungsi untuk format Rupiah (tetap sama)
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Fungsi untuk mengisi fasilitas (akan dipanggil saat modal dibuka)
      async function populateFasilitasCheckboxes(selectedFasilitasIds = []) {
        try {
            const response = await fetch('/api/fasilitas');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const fasilitasData = await response.json();
            fasilitasContainer.innerHTML = '';

            if (fasilitasData.length === 0) {
                fasilitasContainer.innerHTML = '<p>Tidak ada fasilitas tersedia.</p>';
                return;
            }

            fasilitasData.forEach(fasilitas => {
                const div = document.createElement('div');
                div.classList.add('fasilitas-item');
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.id = `fasilitas_${fasilitas.id}`;
                input.name = 'fasilitas[]'; // Nama array untuk fasilitas
                input.value = fasilitas.id;

                // Tandai checkbox jika fasilitas ini sudah terpilih untuk lapangan yang diedit
                if (selectedFasilitasIds.includes(fasilitas.id)) {
                    input.checked = true;
                }

                const label = document.createElement('label');
                label.htmlFor = `fasilitas_${fasilitas.id}`;
                if (fasilitas.gambar_ikon && fasilitas.full_gambar_url) {
                    const iconImg = document.createElement('img');
                    iconImg.src = fasilitas.full_gambar_url;
                    iconImg.alt = fasilitas.nama_fasilitas;
                    iconImg.classList.add('img-icon-small');
                    label.appendChild(iconImg);
                }
                label.append(` ${fasilitas.nama_fasilitas}`);

                div.appendChild(input);
                div.appendChild(label);
                fasilitasContainer.appendChild(div);
            });
        } catch (error) {
            console.error("Gagal mengambil data fasilitas:", error);
            fasilitasContainer.innerHTML = '<p style="color: red;">Gagal memuat fasilitas.</p>';
        }
    }

    // Fungsi baru untuk edit form
    // Fungsi fillEditForm
    async function fillEditForm(lapanganId) {
        try {
            console.log(`Mengambil data untuk edit lapangan ID: ${lapanganId}`);
            const response = await fetch(`/api/lapangans/${lapanganId}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const lapanganData = await response.json();
            console.log("Data lapangan untuk edit:", lapanganData); // Pastikan data di sini lengkap dan benar

            // Set ID lapangan yang sedang diedit
            editingLapanganId = lapanganData.id;

            // Mendapatkan referensi ke elemen-elemen form dan mengisinya
            const formElements = {
                nama_lapangan: document.getElementById('nama_lapangan'),
                lokasi: document.getElementById('lokasi'),
                harga_lapangan: document.getElementById('harga_lapangan'),
                rating: document.getElementById('rating'),
                deskripsi_lapangan: document.getElementById('deskripsi_lapangan'),
                status_aktif: document.getElementById('status_aktif'),
            };

            // Loop melalui elemen form dan isi nilainya
            for (const id in formElements) {
                const element = formElements[id];
                if (element) { // Pastikan elemen ditemukan sebelum mencoba mengisi
                    console.log(`Mengisi ${id} dengan nilai:`, lapanganData[id]); // DEBUG: Cek apa yang akan diisi
                    if (id === 'status_aktif') {
                        element.value = lapanganData[id] ? '1' : '0';
                    } else {
                        element.value = lapanganData[id] || ''; // Gunakan || '' untuk menghindari undefined/null
                    }
                } else {
                    // DEBUG KRITIS: Jika ini muncul di konsol, berarti elemen tidak ditemukan!
                    console.error(`Elemen dengan ID "${id}" tidak ditemukan di DOM saat mencoba mengisi form edit.`);
                    // Anda bisa throw error atau return di sini jika elemen sangat krusial
                    throw new Error(`Elemen form penting tidak ditemukan: ${id}`);
                }
            }

            // Menangani fasilitas (memilih checkbox yang sudah ada)
            const selectedFasilitasIds = lapanganData.fasilitas ? lapanganData.fasilitas.map(f => f.id) : [];
            await populateFasilitasCheckboxes(selectedFasilitasIds);

            // Ubah judul modal
            const modalTitleElement = document.getElementById('modalTitle'); // Cari berdasarkan ID baru
            if(modalTitleElement) {
                modalTitleElement.textContent = `Edit Lapangan: ${lapanganData.nama_lapangan}`;
            } else {
                console.warn("Elemen judul modal dengan ID 'modalTitle' tidak ditemukan."); // Pesan peringatan baru
            }



        } catch (error) {
            console.error('Gagal memuat data lapangan untuk edit:', error);
            alert(`Gagal memuat data lapangan untuk edit: ${error.message}`);
            addLapanganModal.style.display = 'none'; // Sembunyikan modal jika gagal memuat data
        }
    }


    // Event listener untuk tombol search
    if (searchButton && searchButton.tagName === 'BUTTON') {
        searchButton.addEventListener('click', () => {
            const searchText = searchInput.value.toLowerCase();
            console.log("Searching for:", searchText);
            fetchDataLapangan(searchText); // Panggil ulang data dengan filter search
        });
    }

    // Event listener untuk input search (saat menekan Enter)
    searchInput.addEventListener('keyup', (event) => {
        if (event.key === 'Enter') {
            const searchText = event.target.value.toLowerCase();
            console.log("Searching for (Enter):", searchText);
            fetchDataLapangan(searchText); // Panggil ulang data dengan filter search
        }
    });

        // Fungsi renderTableData
    function renderTableData(data) {
        dataTableBody.innerHTML = '';

        console.log("Memulai proses render tabel dengan data:", data);

        if (data.length === 0) {
            dataTableBody.innerHTML = '<tr><td colspan="10" style="text-align: center;">Tidak ada data lapangan.</td></tr>';
            console.log("Tidak ada data lapangan untuk ditampilkan.");
            return;
        }

        data.forEach(lapangan => {
            console.log("Memproses lapangan:", lapangan.id, lapangan.nama_lapangan);

            const row = document.createElement('tr');

            const tdId = document.createElement('td');
            tdId.textContent = lapangan.id;
            row.appendChild(tdId);

            const tdNama = document.createElement('td');
            tdNama.textContent = lapangan.nama_lapangan;
            row.appendChild(tdNama);

            const tdLokasi = document.createElement('td');
            tdLokasi.textContent = lapangan.lokasi;
            row.appendChild(tdLokasi);

            const tdHarga = document.createElement('td');
            tdHarga.textContent = formatRupiah(lapangan.harga_lapangan);
            row.appendChild(tdHarga);

            const tdRating = document.createElement('td');
            tdRating.textContent = lapangan.rating;
            row.appendChild(tdRating);

            const tdDeskripsi = document.createElement('td');
            tdDeskripsi.textContent = lapangan.deskripsi_lapangan;
            row.appendChild(tdDeskripsi);

            const tdGambar = document.createElement('td');
            const img = document.createElement('img');
            img.src = lapangan.full_gambar_url;
            img.alt = lapangan.nama_lapangan;
            img.classList.add('img-thumbnail');
            tdGambar.appendChild(img);
            row.appendChild(tdGambar);

            const tdFasilitas = document.createElement('td');
            const fasilitasDiv = document.createElement('div');
            fasilitasDiv.classList.add('fasilitas-icons');
            console.log(`Fasilitas untuk ${lapangan.id}:`, lapangan.fasilitas);

            if (lapangan.fasilitas && Array.isArray(lapangan.fasilitas)) {
                lapangan.fasilitas.forEach(f => {
                    const p = document.createElement('p');
                    if (f.gambar_ikon && f.full_gambar_url) {
                        const iconImg = document.createElement('img');
                        iconImg.src = f.full_gambar_url;
                        iconImg.alt = f.nama_fasilitas;
                        iconImg.classList.add('img-icon-small');
                        p.appendChild(iconImg);
                        p.append(` ${f.nama_fasilitas}`);
                    } else {
                        p.append(f.nama_fasilitas);
                    }
                    fasilitasDiv.appendChild(p);
                });
            } else {
                console.warn(`Lapangang ${lapangan.id} tidak memiliki array fasilitas yang valid.`);
            }
            tdFasilitas.appendChild(fasilitasDiv);
            row.appendChild(tdFasilitas);


            const tdStatus = document.createElement('td');
            const statusIcon = document.createElement('i');
            if (lapangan.status_aktif) {
                statusIcon.classList.add('fas', 'fa-check-circle', 'status-active');
            } else {
                statusIcon.classList.add('fas', 'fa-times-circle', 'status-inactive');
            }
            tdStatus.appendChild(statusIcon);
            row.appendChild(tdStatus);

            const tdAksi = document.createElement('td');
            const editBtn = document.createElement('a');
            editBtn.href = "#";
            editBtn.classList.add('btn-action', 'edit');
            editBtn.innerHTML = '<i class="fas fa-edit"></i>';
            editBtn.title = `Edit ${lapangan.nama_lapangan}`;

            editBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log(`--- Tombol Edit diklik untuk Lapangan ID: ${lapangan.id} ---`);
                addLapanganModal.style.display = 'flex'; // Tampilkan modal

                // --- PERUBAHAN DI SINI: Panggil fillEditForm dengan setTimeout ---
                setTimeout(() => {
                    fillEditForm(lapangan.id);
                }, 0); // Delay 0ms, ini hanya untuk memastikan event loop sudah kosong
            });

            const deleteBtn = document.createElement('a');
            deleteBtn.href = "#";
            deleteBtn.classList.add('btn-action', 'delete');
            deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            deleteBtn.title = `Hapus ${lapangan.nama_lapangan}`;
            deleteBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                console.log(`--- Tombol Delete diklik untuk Lapangan ID: ${lapangan.id} ---`);
                if (confirm(`Apakah Anda yakin ingin menghapus lapangan "${lapangan.nama_lapangan}"?`)) {
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const response = await fetch(`/api/lapangans/${lapangan.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }

                        alert('Lapangan berhasil dihapus!');
                        fetchDataLapangan();
                    } catch (error) {
                        console.error('Gagal menghapus lapangan:', error);
                        alert(`Gagal menghapus lapangan: ${error.message}`);
                    }
                }
            });

            tdAksi.appendChild(editBtn);
            tdAksi.appendChild(deleteBtn);
            row.appendChild(tdAksi);

            dataTableBody.appendChild(row);
        });
        console.log("Selesai merender data lapangan.");
    }

    // Fungsi fetchDataLapangan dengan dukungan search
    async function fetchDataLapangan(searchTerm = '') {
        try {
            console.log("Fetching data lapangan..."); // DEBUG 9
            let url = '/api/lapangans';
            if (searchTerm) {
                url += `?search=${encodeURIComponent(searchTerm)}`;
            }
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log("Data lapangan berhasil diambil:", data); // DEBUG 10
            renderTableData(data);
        } catch (error) {
            console.error("Gagal mengambil data lapangan:", error); // DEBUG 11
            dataTableBody.innerHTML = '<tr><td colspan="10" style="text-align: center; color: red;">Gagal memuat data. Silakan coba lagi.</td></tr>';
        }
    }

    // Panggil fetchDataLapangan saat halaman dimuat
    fetchDataLapangan();

    // Event listener untuk tombol "Tambah Lapangan"
   if (addLapanganBtn) {
        addLapanganBtn.addEventListener('click', () => {
            console.log('Tombol "Tambah Lapangan" diklik.');
            editingLapanganId = null; // Set ID lapangan yang diedit menjadi null untuk mode tambah
            addLapanganModal.style.display = 'flex'; // Tampilkan modal
            populateFasilitasCheckboxes(); // Isi fasilitas saat modal dibuka
            document.querySelector('#addLapanganModal h3').textContent = 'Tambah Lapangan Baru'; // Set judul
            addLapanganForm.reset(); // Pastikan form bersih saat membuka untuk tambah
            const modalTitleElement = document.getElementById('modalTitle');
            if(modalTitleElement) {
                modalTitleElement.textContent = 'Tambah Lapangan Baru'; // Gunakan ID baru
            }
        });
    }

     // Event listener untuk tombol penutup modal (X dan Batal)
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            console.log('Tombol penutup modal diklik.');
            addLapanganModal.style.display = 'none'; // Sembunyikan modal
            addLapanganForm.reset(); // Reset form saat ditutup
            editingLapanganId = null; // Reset ID lapangan yang diedit saat modal ditutup
        });
    });

    // Menutup modal jika klik di luar area modal content
    window.addEventListener('click', (event) => {
        if (event.target === addLapanganModal) {
            console.log('Klik di luar modal, menutup modal.');
            addLapanganModal.style.display = 'none';
            addLapanganForm.reset();
            editingLapanganId = null; // Reset ID lapangan yang diedit saat modal ditutup
        }
    });

    // Event listener untuk submit form tambah lapangan
    addLapanganForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Form submit Tambah/Edit Lapangan dipicu.');

        const formData = new FormData(addLapanganForm);

        let url = '/api/lapangans';
        let method = 'POST';

        if (editingLapanganId) {
            url = `/api/lapangans/${editingLapanganId}`;
            method = 'POST';
            formData.append('_method', 'PUT');
            console.log(`[DEBUG SUBMIT] URL untuk UPDATE: ${url}`);
            console.log(`[DEBUG SUBMIT] Metode untuk UPDATE: ${method}`);
            console.log(`[DEBUG SUBMIT] ID Lapangan yang dikirim: ${editingLapanganId}`);
        } else {
            console.log(`[DEBUG SUBMIT] URL untuk CREATE: ${url}`);
            console.log(`[DEBUG SUBMIT] Metode untuk CREATE: ${method}`);
        }

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                let errorMessage = errorData.message || `HTTP error! status: ${response.status}`;
                if (errorData.errors) {
                    errorMessage += "\n\nDetail Error:";
                    for (const key in errorData.errors) {
                        errorMessage += `\n- ${key}: ${errorData.errors[key].join(', ')}`;
                    }
                }
                throw new Error(errorMessage);
            }

            const successMessage = editingLapanganId ? 'Lapangan berhasil diperbarui!' : 'Lapangan berhasil ditambahkan!';
            alert(successMessage);
            addLapanganModal.style.display = 'none';
            addLapanganForm.reset();
            editingLapanganId = null; // Pastikan direset setelah submit
            fetchDataLapangan(); // Muat ulang data tabel
        } catch (error) {
            console.error('Gagal menyimpan lapangan:', error); // Ubah pesan error
            alert(`Gagal menyimpan lapangan: ${error.message}`);
        }
    });

});