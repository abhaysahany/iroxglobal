<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('uploads/logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('uploads/logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uploads/logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('uploads/logo/favicon/site.webmanifest') }}" />
    <style>
        .admin-forgot-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .admin-forgot-logo img {
            width: 150px;
            max-width: 100%;
            height: auto;
        }

        .admin-forgot-card .login-brand {
            justify-content: center;
            margin-bottom: 22px;
            text-align: left;
        }

        .admin-forgot-note {
            margin: 0;
            padding: 14px;
            border: 1px solid rgba(132, 90, 223, 0.18);
            border-radius: 10px;
            background: rgba(132, 90, 223, 0.08);
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.5;
        }

        .admin-forgot-actions {
            display: grid;
            gap: 12px;
        }

        .admin-back-link {
            justify-self: center;
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .admin-back-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
    </style>
</head>

<body class="frontend-page">
    <main class="frontend-content">
        <section class="login-card frontend-login-card admin-forgot-card">
            <div class="admin-forgot-logo">
                <img src="{{ asset('uploads/logo/logo-greenlife.png') }}" alt="logo">
            </div>

            <div class="login-brand">
                <span class="brand-mark">A</span>
                <div>
                    <h1>Forgot Password</h1>
                    <p>Recover the admin account password.</p>
                </div>
            </div>

            @if (session()->has('succMsg'))
                <div class="alert alert-success">{{ session('succMsg') }}</div>
            @endif

            <form method="POST" action="{{ route('retrivePassword') }}" class="form-stack">
                @csrf

                <p class="admin-forgot-note">
                    Password will be sent to your registered admin email address.
                </p>

                <div class="admin-forgot-actions">
                    <button type="submit" class="btn btn-primary btn-full">Retrieve Password</button>
                    <a class="admin-back-link" href="{{ route('home') }}">Back to Login</a>
                </div>
            </form>
        </section>
    </main>
</body>

</html>
