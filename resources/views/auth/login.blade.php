<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    {{-- Memuat CSS dari folder public/css --}}
    <link rel="stylesheet" href="/css/login.css">
    {{-- Memuat Font Awesome untuk ikon input --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- TAMBAH INI: SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!-- @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

     {{-- Elemen tersembunyi untuk menyimpan pesan error Laravel --}}
    {{-- Kita akan meng-encode pesan error ke dalam JSON string dan menyimpannya di atribut data-errors --}}
    <div id="error-messages-data" style="display: none;" 
        data-errors="{{ json_encode($errors->all()) }}">
    </div>

    <div class="login-container">
        <div class="logo-area">
            {{-- Menggunakan asset() helper untuk gambar di Laravel --}}
            <img src="{{ asset('assets/logo.png') }}" alt="Logo FS">
        </div>

        <div class="login-box">
            <h2>Admin Login</h2>
            <form action="{{ route('login') }}" method="POST"> {{-- Sesuaikan 'route('login')' dengan route login Anda --}}
                @csrf {{-- Laravel CSRF Token --}}

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                
                <button type="submit" class="login-button">Login</button>
            </form>
            
            <div class="forgot-password">
                <a href="{{ route('ShowPass') }}">Lupa Kata Sandi?</a> {{-- Sesuaikan 'route('password.request')' --}}
            </div>
        </div>
    </div>
{{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- REFERENSIKAN FILE JS BARU ANDA --}}
    <script src="/js/login.js"></script>
</body>
</html>