// public/js/dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    const sidebarNavItems = document.querySelectorAll('.sidebar-nav ul li'); // Pilih semua item li di sidebar

    // Fungsi untuk mengatur item menu yang aktif
    function setActiveMenuItem(clickedItem) {
        // Hapus class 'active' dari semua item menu yang ada
        sidebarNavItems.forEach(item => {
            item.classList.remove('active');
        });

        // Tambahkan class 'active' ke item yang baru diklik
        clickedItem.classList.add('active');

        // Opsional: Simpan item aktif ke localStorage agar tetap aktif setelah refresh
        localStorage.setItem('activeMenuItemId', clickedItem.id);
    }

    // Tambahkan event listener ke setiap item menu
    sidebarNavItems.forEach(item => {
        item.addEventListener('click', function(event) {
            // Mencegah navigasi default jika href="#"
            event.preventDefault(); 
            setActiveMenuItem(this);
        });
    });

    // Periksa localStorage saat halaman dimuat untuk mengembalikan item aktif
    const savedActiveMenuItemId = localStorage.getItem('activeMenuItemId');
    if (savedActiveMenuItemId) {
        const savedActiveItem = document.getElementById(savedActiveMenuItemId);
        if (savedActiveItem) {
            // Panggil setActiveMenuItem tanpa event.preventDefault() untuk inisialisasi
            // karena kita hanya ingin mengatur class, bukan memicu navigasi.
            sidebarNavItems.forEach(item => {
                item.classList.remove('active');
            });
            savedActiveItem.classList.add('active');
        }
    } else {
        // Jika tidak ada yang tersimpan, pastikan "Dashboard" aktif secara default
        const defaultActiveItem = document.getElementById('menu-dashboard');
        if (defaultActiveItem) {
            defaultActiveItem.classList.add('active');
        }
    }
});