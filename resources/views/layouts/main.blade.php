<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- TAMBAH BARIS INI --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}?v=1.0">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-text">Flow State</div>
            </div>
            <div class="user-profile">
                {{-- PASTIKAN ICON INI ADA DAN TERLIHAT --}}
                <div class="profile-icon">
                    <i class="fa-solid fa-user-circle"></i> {{-- Kembali ke ikon Font Awesome --}}
                    {{-- Jika Anda ingin pakai gambar logo, ganti ini dengan: --}}
                    {{-- <img src="{{ asset('assets/logo.png') }}" alt="Logo Anda" class="user-profile-logo"> --}}
                </div>
                <div class="profile-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role"><span class="status-indicator online"></span> Online</span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-title">MENU UTAMA</div>
                <ul>
                    <li id="menu-dashboard"><a href="{{ route('dashboard') }}"><span><i class="fa-solid fa-gauge"></i> Dashboard</span></a></li> {{-- Menggunakan ikon gauge --}}
                    <li id="menu-konsumen"><a href="{{ route('konsumen') }}"><span><i class="fa-solid fa-users"></i> Data Konsumen</span></a></li>
                    <li id="menu-lapangan"><a href="{{ route('lapangan.index') }}"><span><i class="fa-solid fa-futbol"></i> Data Lapangan</span></a></li>
                    <li id="menu-fasilitas"><a href="{{ route('fasilitas.index') }}"><span><i class="fa-solid fa-building"></i> Data Fasilitas</span></a></li>
                    <li id="menu-pemesanan"><a href="{{ route('pemesanan') }}"><span><i class="fa-solid fa-clipboard-list"></i> Data Pemesanan</span></a></li>
                    <li id="menu-pembayaran"><a href="{{ route('pembayaran') }}"><span><i class="fa-solid fa-credit-card"></i> Data Pembayaran</span></a></li>
                    <li id="menu-topup"><a href="{{ route('topup') }}"><span><i class="fa-solid fa-credit-card"></i> Top Up</span></a></li>
                </ul>
                <div class="nav-section-title">TENTANG</div>
                <ul>
                    <li id="menu-informasi"><a href="{{ route('informasi') }}"><span><i class="fa-solid fa-info-circle"></i> Informasi</span></a></li>
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
                <div class="navbar-right">
                </div>
            </nav>

            {{-- Ini adalah tempat konten spesifik halaman akan di-inject --}}
            @yield('content') 
            
        </main>
    </div>

    <script src="/js/dashboard.js"></script>
    {{-- TAMBAHKAN SCRIPT SWEETALERT2 DI SINI --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>