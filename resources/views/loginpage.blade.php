<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIMAS-FTMM</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Override body style khusus untuk halaman login agar sempurna di tengah */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <!-- Shapes & Particles -->
    <div class="shape circle" style="width:150px;height:150px;top:12%;left:8%;"></div>
    <div class="shape square" style="width:110px;height:110px;top:25%;right:12%;"></div>
    <div class="shape triangle" style="bottom:20%;left:20%;"></div>
    <div class="particle" style="left:15%;bottom:0;"></div>
    <div class="particle" style="left:40%;bottom:0;"></div>
    <div class="particle" style="left:70%;bottom:0;"></div>
    <div class="particle" style="left:85%;bottom:0;"></div>

    <!-- Login Container -->
    <div class="login-container" style="animation: none; opacity: 1; transform: translateY(0);">
        <h1>Login</h1>
        <p>Masuk ke akun SIMAS-FTMM Anda</p>

        @if($errors->any())
            <div style="color: #d50000; background: #ffdddd; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                {{ $errors->first('user') ?: 'NIM/Username atau Password salah.' }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="user">NIM / Username</label>
                <input type="text" id="user" name="user" placeholder="Masukkan NIM atau Username" value="{{ old('user') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
            </div>
            <button type="submit" class="btn-login">Log In</button>
        </form>
    </div>
</body>
</html>