@php
$headerMember = null;
$headerMemberId = session('MEMBER_ID');

if ($headerMemberId) {
    $headerMember = \App\Models\MemberDetail::where('memberid', $headerMemberId)->first();
}

$headerName = $headerMember->name ?? session('member.name') ?? 'Member';
$headerEmail = $headerMember->email ?? '-';
$headerMemberCode = $headerMember->memberid ?? $headerMemberId ?? '-';
$headerRank = $headerMember->rank ?? $headerMember->type_id ?? 'Executive';

$headerImage = null;

if ($headerMember) {
    $imageValue = $headerMember->profile_image
        ?? $headerMember->image
        ?? $headerMember->photo
        ?? $headerMember->avatar
        ?? null;

    if ($imageValue) {
        if (\Illuminate\Support\Str::startsWith($imageValue, ['http://', 'https://'])) {
            $headerImage = $imageValue;
        } elseif (\Illuminate\Support\Str::startsWith($imageValue, ['uploads/', 'storage/', 'member/'])) {
            $headerImage = asset($imageValue);
        } else {
            if (file_exists(public_path('uploads/' . $imageValue))) {
                $headerImage = asset('uploads/' . $imageValue);
            } else {
                $headerImage = asset('uploads/profile/' . $imageValue);
            }
        }
    }
}
@endphp
<header class="member-header glass-surface">
    <div class="header-left">
        <button class="icon-button js-sidebar-toggle" type="button" aria-label="Open navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        @php
      $currentPageTitle = trim($__env->yieldContent('page_title'));
        // if ($currentPageTitle === '') {
        //     $currentPageTitle = trim($__env->yieldContent('title'));
        // }
        if ($currentPageTitle === '') {
            $currentPageTitle = $pageTitle ?? 'Dashboard';
        }
        @endphp
        <div>
            <span class="eyebrow">Member Panel</span>
            <h1 data-current-page-title>{{ $currentPageTitle }}</h1>
        </div>
    </div>

    {{-- Header search bar temporarily removed --}}
    {{--
    <div class="header-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" placeholder="Search anything..." aria-label="Search">
    </div>
    --}}

    <div class="header-actions">
        <div class="header-dropdown">
            <button class="icon-button notification-button js-dropdown-toggle" type="button" aria-label="Notifications">
                <i class="fa-regular fa-bell"></i>
                <span class="notification-dot"></span>
            </button>
            <div class="dropdown-menu glass-surface">
                <strong>Notifications</strong>
                <span>New referral joined your network.</span>
                <span>Wallet balance updated.</span>
            </div>
        </div>

        {{-- Language dropdown removed: English-only project --}}
        {{--
        <div class="gl-lang-dropdown" data-gl-lang-dropdown>
            <button type="button" class="gl-lang-toggle" data-gl-lang-toggle aria-expanded="false">
                <span class="gl-lang-badge" data-gl-lang-badge>EN</span>
                <span class="gl-lang-label" data-gl-lang-label>English</span>
                <span class="gl-lang-chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </button>

            <div class="gl-lang-menu" data-gl-lang-menu>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="en">English</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="hi">Hindi</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="de">German</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="es">Spanish</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="fr">French</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="ar">Arabic</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="pt">Portuguese</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="ru">Russian</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="zh">Chinese</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="ja">Japanese</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="ko">Korean</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="it">Italian</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="tr">Turkish</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="id">Indonesian</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="bn">Bengali</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="ur">Urdu</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="vi">Vietnamese</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="th">Thai</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="nl">Dutch</button>
                <button type="button" class="gl-lang-option" data-gl-lang-option data-lang-code="pl">Polish</button>
            </div>
        </div>
        --}}

        <button class="icon-button theme-toggle js-theme-toggle" type="button" aria-label="Toggle theme">
            <i class="fa-solid fa-sun"></i>
        </button>

        <div class="header-dropdown profile-dropdown">
            <button class="profile-button js-dropdown-toggle" type="button">
                @if ($headerImage)
                    <img src="{{ $headerImage }}" alt="{{ $headerName }}" class="profile-avatar-img">
                @else
                    <img src="{{ asset('member/assets/images/user-avatar.png') }}" alt="{{ $headerName }} avatar" class="profile-avatar-img">
                @endif
                <span>
                    <strong>{{ $headerName }}</strong>
                    <small>{{ $headerRank }}</small>
                </span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu glass-surface profile-menu">
                <a href="{{ route('member.profile.summary') }}" class="header-profile-action-btn">
                    <i class="fa-solid fa-user"></i>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
