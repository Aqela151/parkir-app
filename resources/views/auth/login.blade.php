<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkSys - Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: #F5F0E8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            padding: 48px 44px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.5px;
            margin-bottom: 32px;
        }

        .logo .park { color: #1C1C1E; }
        .logo .sys  { color: #F8C61E; }

        .card h1 {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .card p {
            font-size: 13px;
            color: #aaa;
            margin-bottom: 32px;
        }

        /* ERROR */
        .alert-error {
            background: #fff5f5;
            border: 1px solid #ffd0d0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #e05555;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* FORM */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #888;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 13px;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            background: #faf9f7;
            border: 1.5px solid #ede9e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Manrope', sans-serif;
            color: #1a1a1a;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input::placeholder { color: #ccc; }

        .form-input:focus {
            border-color: #F8C61E;
            box-shadow: 0 0 0 3px rgba(248,198,30,0.1);
            background: #fff;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #ccc;
            font-size: 13px;
            padding: 0;
        }

        .toggle-password:hover { color: #999; }

        /* REMEMBER */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .form-check input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #F8C61E;
            cursor: pointer;
        }

        .form-check label {
            font-size: 13px;
            color: #aaa;
            cursor: pointer;
        }

        /* BUTTON */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #1C1C1E;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-login:hover { background: #2a2a2e; }

        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: #ccc;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo">
            <span class="park">Park</span><span class="sys">Sys</span>
        </div>

        <h1>Masuk</h1>
        <p>Silakan login untuk melanjutkan</p>

        @if ($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-input"
                        placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" id="passwordInput"
                        class="form-input" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="footer">&copy; {{ date('Y') }} ParkSys</div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>
</html>