@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-table-columns', 'route' => 'member.dashboard'],
        [
            'label' => 'My Profile',
            'icon' => 'fa-user',
            'route' => 'member.profile',
            'children' => [
                ['label' => 'User Summary', 'route' => 'member.profile.summary'],
                ['label' => 'Change Password', 'route' => 'member.profile.change-password'],
                ['label' => 'Wallet Address', 'route' => 'member.profile.wallet-address'],
                ['label' => 'KYC Verification', 'route' => 'member.profile.kyc']
            ],
        ],
        [
            'label' => 'Add Fund',
            'icon' => 'fa-circle-plus',
            'route' => 'member.import-fund',
            'children' => [
                ['label' => 'Add Fund', 'route' => 'member.import-fund'],
                ['label' => 'Add Fund History', 'route' => 'member.add-fund.history'],
            ],
        ],
        [
            'label' => 'Activation',
            'icon' => 'fa-key',
            'route' => 'member.activation.index',
        ],

        [
            'label' => 'Incomes',
            'icon' => 'fa-sack-dollar',
            'route' => 'member.incomes.daily',
            'children' => [
                ['label' => 'Daily Income', 'route' => 'member.incomes.daily'],
                ['label' => 'Referral Income', 'route' => 'member.incomes.referral'],
                ['label' => 'Level Income', 'route' => 'member.incomes.level'],
                ['label' => 'Rank Income', 'route' => 'member.incomes.rank'],
                ['label' => 'Global Rec Income', 'route' => 'member.incomes.globalRec'],
                ['label' => 'Global Rec Star Income', 'route' => 'member.incomes.globalRecStar'],
            ],
        ],
        [
            'label' => 'P2P Section',
            'icon' => 'fa-right-left',
            'route' => 'member.p2p.wallet',
            'children' => [
                ['label' => 'P2P Wallet', 'route' => 'member.p2p.wallet'],
                ['label' => 'P2P Wallet History', 'route' => 'member.p2p.wallet-history'],
            ],
        ],
        [
            'label' => 'Wallet',
            'icon' => 'fa-wallet',
            'route' => 'member.wallet.income',
            'children' => [
                ['label' => 'Main Wallet', 'route' => 'member.wallet.income'],
                ['label' => 'Withdrawal History', 'route' => 'member.wallet.withdrawal-history'],
                ['label' => 'All Transactions', 'route' => 'member.wallet.all-transactions'],
                ['label' => 'Wallet to P2P', 'route' => 'member.wallet.to-p2p'],
                ['label' => 'Wallet to P2P History', 'route' => 'member.wallet.to-p2p-history'],
            ],
        ],
        [
            'label' => 'My Referral',
            'icon' => 'fa-users-line',
            'route' => 'member.referral.genealogy',
            'children' => [
                ['label' => 'Genealogy', 'route' => 'member.referral.genealogy'],
                ['label' => 'Direct Members', 'route' => 'member.referral.direct-members'],
                //['label' => 'Level Member Table', 'route' => 'member.referral.level-members'],
            ],
        ],
        [
            'label' => 'Support',
            'icon' => 'fa-headset',
            'route' => 'member.support.create-ticket',
            'children' => [
                ['label' => 'Create New Ticket', 'route' => 'member.support.create-ticket'],
                ['label' => 'Support Tickets', 'route' => 'member.support.tickets'],
            ],
        ],
        // ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => null],
    ];
@endphp

<!--{{-- Settings menu removed as requested --}}-->
<!--{{---->
<!--        ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => null],-->
<!----}}-->

<!--<aside class="member-sidebar glass-surface">-->

<style>
    .sidebar-logout-form {
        margin: 0;
    }

    .sidebar-logout-button {
        width: 100%;
        border: 0;
        background: transparent;
        cursor: pointer;
        text-align: left;
    }
</style>

<aside class="member-sidebar glass-surface">
    
    <a class="brand" href="{{ route('member.dashboard') }}">
        <img src="{{ asset('uploads/logo/logo-greenlife.png') }}" alt="logo">
    </a>

    <nav class="sidebar-nav" aria-label="Member navigation">
        @foreach ($menuItems as $item)
            @php
                $hasChildren = isset($item['children']);
                $isActive = false;
                if ($hasChildren) {
                    if ($item['label'] === 'My Profile') {
                        $isActive = request()->routeIs('member.profile') || request()->routeIs('member.profile.*');
                    } elseif ($item['label'] === 'Add Fund') {
                        $isActive = request()->routeIs('member.add-fund') || request()->routeIs('member.add-fund.*');
                    } elseif ($item['label'] === 'My Referral') {
                        $isActive = request()->routeIs('member.referral.*');
                    } elseif ($item['label'] === 'Incomes') {
                        $isActive = request()->routeIs('member.incomes.*');
                    } elseif ($item['label'] === 'P2P Section') {
                        $isActive = request()->routeIs('member.p2p.*');
                    } elseif ($item['label'] === 'Wallet') {
                        $isActive = request()->routeIs('member.wallet.*');
                    } elseif ($item['label'] === 'Support') {
                        $isActive = request()->routeIs('member.support.*');
                    }
                } else {
                    if ($item['label'] === 'Activation') {
                        $isActive =
                            request()->routeIs('member.activation') || request()->routeIs('member.activation.*');
                    } else {
                        $isActive = $item['route'] ? request()->routeIs($item['route']) : false;
                    }
                }
            @endphp
            @if ($hasChildren)
                <div class="sidebar-dropdown-wrapper {{ $isActive ? 'is-expanded' : '' }}">
                    <a href="#" class="sidebar-link js-sidebar-dropdown-toggle {{ $isActive ? 'is-active' : '' }}"
                        data-sidebar-dropdown-toggle>
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                        <i class="fa-solid fa-chevron-down dropdown-arrow"
                            style="margin-left: auto; font-size: 11px; transition: transform 0.2s; {{ $isActive ? 'transform: rotate(180deg);' : '' }}"></i>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ $isActive ? 'display: flex;' : 'display: none;' }} padding-left: 20px; margin-top: 4px; flex-direction: column; gap: 4px;">
                        @foreach ($item['children'] as $child)
                            @php
                                $isChildActive = $child['route'] ? request()->routeIs($child['route']) : false;
                            @endphp
                            <a href="{{ $child['route'] ? route($child['route']) : '#' }}"
                                class="sidebar-link {{ $isChildActive ? 'is-active' : '' }}"
                                style="min-height: 36px; font-size: 13px; font-weight: 700; gap: 8px;">
                                <i class="fa-solid fa-circle"
                                    style="font-size: 6px; width: 10px; color: var(--gl-muted);"></i>
                                <span>{{ $child['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    class="sidebar-link {{ $isActive ? 'is-active' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
             @endif
        @endforeach

        <form method="POST" action="{{ route('member.logout') }}" class="sidebar-logout-form">
            @csrf
            <button type="submit" class="sidebar-link sidebar-logout-button">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
