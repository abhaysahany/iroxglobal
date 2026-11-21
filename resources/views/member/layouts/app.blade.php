<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('detailsApp.name'))</title>
    <meta name="member-page-title" content="{{ trim($__env->yieldContent('page_title')) ?: trim($__env->yieldContent('title')) ?: ($pageTitle ?? 'Dashboard') }}">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('member/assets/css/member.css') }}">
</head>
<body class="theme-light @yield('body_class')">
    <script>
        (function () {
            var theme = window.greenlifeInitialTheme;
            if (theme === 'theme-light' || theme === 'theme-dark') {
                document.body.classList.remove('theme-light', 'theme-dark');
                document.body.classList.add(theme);
            }
        })();
    </script>

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('member/assets/js/member.js') }}"></script>
     <script>
        $(document).ready(function() {
            //fund transfer memberid check
            $('#memberid').on('input', function() {
                var memberid = $(this).val();
                var csrf = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/member/getMember',
                    type: 'POST',
                    data: {
                        'memberid': memberid,
                        _token: csrf,
                    },
                    success: function(response) {
                        $('#memMsg').html(response['data']);
                    }
                });
            });
        });
    </script>
    
</body>
</html>
