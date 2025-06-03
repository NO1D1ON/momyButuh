<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="dashboard-container">
        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-text">Flow State</div>
                {{-- Tombol tutup hamburger di sidebar Dihapus --}}
            </div>
            <div class="user-profile">
                <div class="profile-icon"><i class="fa-solid fa-user-circle"></i></div>
                <div class="profile-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role"><span class="status-indicator online"></span> Online</span>
                    <!-- <span class="user-role">Online</span> -->
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-title">MENU UTAMA</div>
                <ul>
                    {{-- Beri id pada setiap li agar mudah diakses oleh JS --}}
                    <li id="menu-dashboard" class="active"><a href="#"><span><i class="fa-solid fa-tachometer-alt"></i> Dashboard</span></a></li>
                    <li id="menu-konsumen"><a href="#"><span><i class="fa-solid fa-users"></i> Data Konsumen</span></a></li>
                    <li id="menu-lapangan"><a href="#"><span><i class="fa-solid fa-futbol"></i> Data Lapangan</span></a></li>
                    <li id="menu-fasilitas"><a href="#"><span><i class="fa-solid fa-building"></i> Data Fasilitas</span></a></li>
                    <li id="menu-pemesanan"><a href="#"><span><i class="fa-solid fa-clipboard-list"></i> Data Pemesanan</span></a></li>
                    <li id="menu-pembayaran"><a href="#"><span><i class="fa-solid fa-credit-card"></i> Data Pembayaran</span></a></li>
                </ul>
                <div class="nav-section-title">TENTANG</div>
                <ul>
                    <li id="menu-informasi"><a href="#"><span><i class="fa-solid fa-info-circle"></i> Informasi</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="{{ route('logout') }}" class="logout-btn"><span><i class="fa-solid fa-sign-out-alt"></i> Log Out</span></a>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="content" id="mainContent">
            {{-- Navbar --}}
            <nav class="navbar">
                {{-- Tombol buka hamburger di navbar Dihapus --}}
                <div class="navbar-right">
                    {{-- Area untuk elemen di kanan navbar --}}
                </div>
            </nav>

            {{-- Dashboard Content --}}
            <div class="dashboard-content">
                <div class="center-content">
                    <div class="main-logo">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo FS">
                    </div>
                    <p class="welcome-text">Welcome Admin {{ Auth::user()->name }} </p>
                </div>
            </div>
        </main>
    </div>
    
    {{-- Memuat JavaScript untuk efek aktif menu --}}
    <script src="/js/dashboard.js"></script>
</body>
</html>