<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('detailsApp.name'))</title>
    <meta name="member-page-title"
        content="{{ trim($__env->yieldContent('page_title')) ?: trim($__env->yieldContent('title')) ?: $pageTitle ?? 'Dashboard' }}">
    <script>
        window.greenlifeInitialTheme = localStorage.getItem('greenlife-theme') || 'theme-light';
    </script>
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('uploads/logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('uploads/logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uploads/logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('uploads/logo/favicon/site.webmanifest') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('member/assets/css/member.css') }}">
   <script>
        (function() {
            var theme = window.greenlifeInitialTheme;
            if (theme === 'theme-light' || theme === 'theme-dark') {
                document.body.classList.remove('theme-light', 'theme-dark');
                document.body.classList.add(theme);
            }
        })();
    </script>

    <style>
        .error-card {
            text-align: center;
            overflow: hidden;
        }

        .error-code {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: min(100%, 230px);
            min-height: 120px;
            margin: 0 auto 18px;
            border-radius: 26px;
            background: linear-gradient(135deg, rgba(238, 249, 233, 0.92), rgba(210, 240, 198, 0.72));
            color: #2c7f17;
            font-size: clamp(54px, 9vw, 96px);
            font-weight: 900;
            line-height: 1;
            box-shadow: inset 0 0 0 1px rgba(61, 154, 29, 0.12);
        }

        .error-code::after {
            content: "";
            position: absolute;
            right: 20px;
            bottom: 16px;
            width: 76px;
            height: 46px;
            border-radius: 100% 0 100% 0;
            background: rgba(76, 175, 80, 0.14);
            transform: rotate(24deg);
            z-index: -1;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 22px;
        }

        .error-action-link {
            min-width: 180px;
            min-height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 14px;
            font-size: 14px;
            font-weight: 900;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .error-action-link.primary {
            background: linear-gradient(135deg, #3d9a1d, #226915);
            color: #ffffff;
            box-shadow: 0 16px 30px rgba(45, 127, 23, 0.22);
        }

        .error-action-link.secondary {
            background: rgba(238, 249, 233, 0.82);
            color: #226915;
            border: 1px solid rgba(61, 154, 29, 0.16);
        }

        .error-action-link:hover {
            transform: translateY(-2px);
        }

        .error-help-text {
            margin-top: 18px;
            color: #6d8068;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.6;
        }

        @media (max-width: 575px) {
            .error-actions {
                grid-template-columns: 1fr;
            }

            .error-code {
                width: 100%;
                min-height: 105px;
                border-radius: 20px;
            }
        }
    </style>

</head>

<body class="theme-light @yield('body_class')">
 

    <main class="member-page auth-page error-page">
        <button class="theme-toggle floating-theme-toggle js-theme-toggle" type="button" aria-label="Toggle theme">
            <i class="fa-solid fa-sun"></i>
        </button>

        <section class="auth-visual">
            <a class="auth-logo" href="{{ route('home') }}">
                <img src="{{ asset('member/assets/images/logo-greenlife.png') }}" alt="logo">
            </a>

            <div class="auth-copy">
                <h1>Oops! <span>Page Lost</span></h1>
                <p>The page you are looking for is not available or may have been moved.</p>
            </div>

            <div class="feature-list compact-features">
                <div><i class="fa-solid fa-shield-halved"></i><span>Secure Platform</span></div>
                <div><i class="fa-solid fa-earth-americas"></i><span>Global Community</span></div>
                <div><i class="fa-solid fa-seedling"></i><span>Endless Growth</span></div>
                <div><i class="fa-solid fa-headset"></i><span>Member Support</span></div>
            </div>

            @include('admin.partials.footer')
        </section>

        
        <section class="auth-panel-wrap">
            <div class="auth-card error-card glass-surface">
                <div class="card-leaf-mark"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="error-code">404</div>
                <h2>Page Not Found</h2>
                <p>This link is broken, expired, or you do not have access to this page.</p>

                <div class="error-actions">
                    <a class="error-action-link primary" href="{{ route('home') }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Back to Login</span>
                    </a>
                </div>

                <div class="error-help-text">
                    If you reached this page from your admin panel, please go back and try again.
                </div>
            </div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('member/assets/js/member.js') }}"></script>
</body>

</html>
