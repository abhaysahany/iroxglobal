<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('uploads/logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('uploads/logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uploads/logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('uploads/logo/favicon/site.webmanifest') }}" />
    <style>
        .admin-login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .admin-login-logo img {
            width: 150px;
            max-width: 100%;
            height: auto;
        }

        .admin-login-card .login-brand {
            justify-content: center;
            text-align: left;
            margin-bottom: 22px;
        }

        .admin-login-card .form-stack {
            gap: 16px;
        }

        .admin-login-card .form-group input {
            min-height: 48px;
            border-radius: 10px;
            background: #ffffff;
        }

        .admin-login-card .form-group input::placeholder {
            color: #9aa4b2;
        }

        .admin-captcha-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .admin-captcha-image-wrapper {
            display: flex;
            align-items: center;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            height: 48px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .admin-captcha-image-wrapper img {
            display: block;
            height: 46px;
            width: 140px;
            cursor: pointer;
            user-select: none;
        }

        .admin-captcha-refresh {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            width: 48px;
            min-height: 48px;
            border-radius: 10px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 16px;
        }

        .admin-captcha-refresh:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .admin-captcha-refresh i {
            transition: transform 0.3s ease;
        }

        .admin-captcha-refresh:active i {
            transform: rotate(180deg);
        }

        .admin-login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: -2px;
        }

        .admin-login-options .check-row {
            margin: 0;
        }

        .admin-forgot-link {
            display: inline-flex;
            justify-self: end;
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .admin-forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        @media (max-width: 520px) {
            .admin-login-options {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body class="frontend-page">
    <main class="frontend-content">
        <section class="login-card frontend-login-card admin-login-card">
            <div class="admin-login-logo">
                <img src="{{ asset('uploads/logo/logo-greenlife.png') }}" alt="logo">
            </div>
            <div class="login-brand">
                <span class="brand-mark">A</span>
                <div>
                    <h1>Admin Login</h1>
                    <p>Sign in to manage your dashboard.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @foreach (['failedMsg', 'loginmsg'] as $messageKey)
                @if (session($messageKey))
                    <div class="alert alert-danger">{{ session($messageKey) }}</div>
                @endif
            @endforeach

            <form action="{{ url('/login') }}" method="POST" class="form-stack">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter email" autocomplete="email" autofocus required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                    <a class="admin-forgot-link" href="{{ url('/forget-password') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password"
                        autocomplete="current-password" required>
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="captcha">Security Verification</label>
                    <div class="admin-captcha-row" style="margin-bottom: 8px;">
                        <div class="admin-captcha-image-wrapper" title="Click to refresh captcha" onclick="refreshCaptcha()">
                            <img src="{{ url('/captcha') }}" id="captchaImage" alt="Captcha">
                        </div>
                        <button type="button" class="admin-captcha-refresh" onclick="refreshCaptcha()" title="Refresh Captcha">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                    </div>
                    <input type="text" id="captcha" name="captcha" placeholder="Enter Captcha Code"
                        autocomplete="off" required style="letter-spacing: 2px; font-weight: 700; text-transform: uppercase;">
                    @error('captcha')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-login-options">
                    <label class="check-row">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember Me</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">Login</button>
            </form>
        </section>
    </main>

    <script>
        function refreshCaptcha() {
            var img = document.getElementById('captchaImage');
            if (img) {
                img.src = '{{ url('/captcha') }}?' + new Date().getTime();
            }
        }
    </script>
</body>

</html>
