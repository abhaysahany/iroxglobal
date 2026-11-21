@php
    $headerAdmin = session('admin_id') ? \App\Models\Admin::find(session('admin_id')) : null;
    $headerAdminName = $headerAdmin?->name ?? session('admin_name', 'Admin');
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="icon-button menu-toggle" aria-label="Toggle sidebar" data-sidebar-toggle>
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="admin-title-wrap">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap');
                .admin-title {
                    font-family: 'Montserrat', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                    font-weight: 700;
                    font-size: 1.2rem;
                    letter-spacing: 0.6px;
                    margin: 0;
                    /* gradient stylish text */
                    background: linear-gradient(90deg, #6f48c9 0%, #3074eb 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }
                @media (max-width: 600px) {
                    .admin-title { font-size: 1rem; }
                }
            </style>

            <h1 class="admin-title">Admin Panel</h1>
        </div>
    </div>

    <div class="topbar-actions">
        {{-- <button type="button" class="header-icon" title="Language">EN</button>
        <button type="button" class="header-icon" title="Dark mode">DM</button>
        <button type="button" class="header-icon has-dot" title="Notifications">N</button>
        <button type="button" class="header-icon" title="Apps">AP</button>
        <button type="button" class="header-icon" title="Fullscreen">FS</button> --}}

        <div class="admin-profile">
            @if ($headerAdmin?->profile_image)
                <img src="{{ asset($headerAdmin->profile_image) }}" class="admin-header-avatar" alt="Admin">
            @else
                <span class="profile-avatar">{{ strtoupper(substr($headerAdminName, 0, 1)) }}</span>
            @endif
            <div>
                <strong>{{ $headerAdminName }}</strong>
                <small>Administrator</small>
            </div>
        </div>

        {{-- <button type="button" class="header-icon" title="Settings">ST</button> --}}

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline">Logout</button>
        </form>
    </div>
</header>
