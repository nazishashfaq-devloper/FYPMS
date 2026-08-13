<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VUFYPMS Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }

        body {
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#e9edf3;
            padding:24px;
        }

       .login-shell {
    width:100%;
    max-width:440px;
    display:flex;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 25px 60px rgba(15,30,70,.18);
}

        /* ===== LEFT PANEL ===== */
        .left {
            width:42%;
            position:relative;
            background:linear-gradient(165deg,#0b1d42 0%,#16306b 55%,#1d3f86 100%);
            color:#fff;
            padding:48px 40px 0;
            display:flex;
            flex-direction:column;
        }

        .vu-logo-box {
            width:84px;
            height:64px;
            border:2px solid rgba(255,255,255,.85);
            border-radius:4px;
            display:flex;
            align-items:center;
            justify-content:center;
            margin-bottom:18px;
            font-family:'Times New Roman', serif;
        }
        .vu-logo-box span {
            font-size:30px;
            font-weight:700;
            letter-spacing:1px;
        }

        .left .uni-name {
            font-size:15px;
            font-weight:600;
            line-height:1.4;
            margin-bottom:28px;
            opacity:.95;
        }

        .left h2 {
            font-size:25px;
            font-weight:800;
            line-height:1.3;
            margin-bottom:18px;
        }

        .divider {
            width:60px;
            height:3px;
            background:linear-gradient(90deg,#7aa6ff,#ff8fb1);
            border-radius:2px;
            margin-bottom:22px;
        }

        .left p.tagline {
            font-size:14px;
            line-height:1.6;
            opacity:.85;
            max-width:260px;
        }

        .building-art {
            position:relative;
            margin-top:auto;
            height:230px;
            background:linear-gradient(180deg, rgba(11,29,66,0) 0%, rgba(8,18,40,.55) 55%, rgba(5,12,28,.85) 100%);
            overflow:hidden;
        }
        .building-art svg { position:absolute; bottom:0; left:0; width:100%; height:100%; opacity:.9; }

        .left-footer {
            position:relative;
            text-align:center;
            font-size:11.5px;
            line-height:1.6;
            opacity:.8;
            padding:14px 20px 22px;
            background:rgba(5,12,28,.85);
        }

        /* ===== RIGHT PANEL ===== */
     .right {
    width:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:50px 44px;
}

        .login-box { width:100%; max-width:380px; }

        .welcome-icon {
            width:88px;
            height:88px;
            margin:0 auto 22px;
            border-radius:50%;
            background:#dce8fb;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:36px;
            color:#1d4ed8;
        }

        .login-box h1 {
            text-align:center;
            font-size:30px;
            font-weight:800;
            color:#101a33;
            margin-bottom:6px;
        }

        .login-box p.subtitle {
            text-align:center;
            font-size:14px;
            color:#7c8597;
            margin-bottom:30px;
        }

        .field { position:relative; margin-bottom:18px; }
        .field i.icon-left {
            position:absolute;
            left:16px;
            top:50%;
            transform:translateY(-50%);
            color:#8b93a7;
            font-size:16px;
        }
        .field input {
            width:100%;
            padding:14px 16px 14px 44px;
            border:1.5px solid #e1e5ee;
            border-radius:10px;
            font-size:14.5px;
            outline:none;
            color:#1f2333;
            transition:border-color .15s ease, box-shadow .15s ease;
        }
        .field input::placeholder { color:#9aa1b4; }
        .field input:focus {
            border-color:#2952b8;
            box-shadow:0 0 0 3px rgba(41,82,184,.12);
        }
        .field .toggle-eye {
            position:absolute;
            right:16px;
            top:50%;
            transform:translateY(-50%);
            color:#8b93a7;
            font-size:16px;
            cursor:pointer;
            background:none;
            border:none;
        }

        .btn-login {
            width:100%;
            padding:14px;
            background:linear-gradient(135deg,#15306b,#1d4ed8);
            color:#fff;
            border:none;
            border-radius:10px;
            font-size:15.5px;
            font-weight:700;
            letter-spacing:.2px;
            cursor:pointer;
            margin-top:6px;
            transition:opacity .15s ease, transform .15s ease;
        }
        .btn-login:hover { opacity:.92; transform:translateY(-1px); }

        .forgot-link {
            display:block;
            text-align:center;
            margin-top:18px;
            font-size:13.5px;
            color:#2952b8;
            text-decoration:none;
            font-weight:600;
        }
        .forgot-link:hover { text-decoration:underline; }

        .alert-box {
            padding:10px 14px;
            border-radius:8px;
            margin-bottom:16px;
            font-size:13px;
        }
        .alert-error { background:#fdecea; color:#b3261e; }
        .alert-success { background:#e6f4ea; color:#1e7e34; }
@media (max-width: 480px) {
    .right { padding:40px 26px; }
}
       
    </style>
</head>

<body>

<div class="login-shell">

    

    <!-- RIGHT PANEL -->
    <div class="right">
        <div class="login-box">

            <div class="welcome-icon"><i class="bi bi-mortarboard-fill"></i></div>

            <h1>Welcome</h1>
            <p class="subtitle">Please login to your account</p>

            @if ($errors->any())
                <div class="alert-box alert-error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="alert-box alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <i class="bi bi-person-fill icon-left"></i>
                    <input type="text" name="email" placeholder="User ID" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <i class="bi bi-lock-fill icon-left"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Password" required>
                    <button type="button" class="toggle-eye" onclick="togglePassword()">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </button>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>

        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const eye = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('bi-eye-fill');
            eye.classList.add('bi-eye-slash-fill');
        } else {
            input.type = 'password';
            eye.classList.remove('bi-eye-slash-fill');
            eye.classList.add('bi-eye-fill');
        }
    }
</script>

</body>
</html>
