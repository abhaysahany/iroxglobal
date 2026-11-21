<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('uploads/logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('uploads/logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uploads/logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('uploads/logo/favicon/site.webmanifest') }}" />
</head>
<body class="admin-body">
    <div class="admin-shell">
        @include('admin.partials.sidebar')
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <div class="admin-main">
            @include('admin.partials.header')

            <main class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('admin/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            //fund transfer memberid check
            $('#memberid').on('input', function() {
                var memberid = $(this).val();
                var csrf = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/admin/getMember',
                    type: 'POST',
                    data: {
                        'memberid': memberid,
                        _token: csrf,
                    },
                    success: function(response) {
                        $('#memMsg').html(response['data']);

                        var nameTarget = $('#memberid').data('member-name-target');
                        if (nameTarget) {
                            $(nameTarget).val(response.code === 1 ? (response.name || '') : '');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
