<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
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

        .admin-otp-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .admin-otp-row .form-group {
            flex: 1 1 auto;
            min-width: 0;
        }

        .admin-otp-button {
            align-self: flex-start;
            flex: 0 0 auto;
            height: 48px;
            min-height: 48px;
            margin: 0;
            white-space: nowrap;
        }

        .admin-otp-message {
            display: block;
            margin-top: 6px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
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
            .admin-otp-row {
                flex-direction: column;
            }

            .admin-otp-button {
                width: 100%;
            }

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

            @if (session('OtpMsg'))
                <div class="alert alert-success">{{ session('OtpMsg') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="form-stack">
                @csrf

                <div class="form-group">
                    <label for="otp">OTP</label>
                    <div class="admin-otp-row">
                        <div class="form-group">
                            <input type="text" id="otp" name="otp" placeholder="Enter OTP"
                                autocomplete="one-time-code" @required(session()->has('admin_otp'))>
                            @error('otp')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <a href="{{ url('/send-admin-otp') }}" class="btn btn-primary admin-otp-button">Send OTP</a>
                    </div>
                    <!--@if (session()->has('admin_otp'))-->
                    <!--    <span class="admin-otp-message">{{ session('admin_otp') }}</span>-->
                    <!--@endif-->
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter email" autocomplete="email" autofocus>
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
                        autocomplete="current-password">
                    @error('password')
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
</body>

</html>
