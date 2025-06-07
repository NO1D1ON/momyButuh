<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <div class="login-container">
        <div class="logo-area">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo FS">
        </div>

        <div class="login-box">
            <h2>Lupa Kata Sandi</h2>

            <form method="POST" action="#">
                @csrf

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Alamat Email" required>
                </div>

                <button type="submit" class="login-button">Kirim Link Reset</button>
            </form>

            <div class="forgot-password">
                <a href="{{ route('login') }}">Kembali ke Login</a>
            </div>
        </div>
    </div>

</body>
</html>
