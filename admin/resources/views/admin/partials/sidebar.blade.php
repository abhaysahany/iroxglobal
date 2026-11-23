@php
    $sidebarSetting = \App\Models\Setting::first();
    $sidebarWebsiteName = $sidebarSetting?->website_name ?: 'Admin';
    $sidebarLogoWidth = $sidebarSetting?->website_logo_width ?: 110;
    $sidebarLogoHeight = $sidebarSetting?->website_logo_height ?: 64;
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-mark" style="width: {{ $sidebarLogoWidth }}px; height: {{ $sidebarLogoHeight }}px;">
            @if ($sidebarSetting?->website_logo)
                <img src="{{ asset($sidebarSetting->website_logo) }}" alt="{{ $sidebarWebsiteName }}">
            @else
                {{ strtoupper(substr($sidebarWebsiteName, 0, 1)) }}
            @endif
        </span>
    </div>

    <nav class="sidebar-menu">
        <p class="menu-label">Main</p>

        <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
            <span class="menu-text">Dashboard</span>
            <span class="menu-arrow">></span>
        </a>

        <p class="menu-label">Manage</p>
        
        <a href="{{ route('admin.addon-data.index') }}"
            class="menu-link {{ request()->routeIs('admin.addon-data.*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-image"></i></span>
            <span class="menu-text">Add On Data</span>
        </a>

        <a href="{{ route('admin.announcements.index') }}" class="menu-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-bullhorn"></i></span>
            <span class="menu-text">Announcements</span>
        </a>
        
          <a href="{{ url('/admin/dash-images') }}"
            class="menu-link {{ request()->routeIs('admin/dash-images') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-image"></i></span>
            <span class="menu-text">Dash Images</span>
        </a>
          <a href="{{ url('/admin/deduction') }}"
            class="menu-link {{ request()->routeIs('admin/deduction') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-circle-minus"></i></span>
            <span class="menu-text">Deduction</span>
        </a>

        <a href="{{ route('admin.profile.index') }}" class="menu-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-user-shield"></i></span>
            <span class="menu-text">Admin Profile</span>
        </a>

        <details class="sidebar-dropdown" {{ request()->routeIs('admin.funds.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.funds.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-wallet"></i></span>
                <span class="menu-text">Funds Management</span>
                <span class="menu-arrow">></span>
            </summary>

            <div class="sidebar-submenu">
                <a href="{{ route('admin.funds.add') }}" class="{{ request()->routeIs('admin.funds.add*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>Add Funds</span>
                </a>
                <a href="{{ route('admin.funds.deduct') }}" class="{{ request()->routeIs('admin.funds.deduct*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-minus"></i>
                    <span>Deduct Funds</span>
                </a>
                <a href="{{ route('admin.funds.details') }}" class="{{ request()->routeIs('admin.funds.details') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Funds Details</span>
                </a>
                <a href="{{ route('admin.funds.fund.details') }}"
                    class="{{ request()->routeIs('admin.funds.fund.details') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Import Funds Details</span>
                </a>
            </div>
        </details>
         <a href="{{ route('admin.members.index') }}" class="menu-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
            <span class="menu-text">Manage Members</span>
        </a>
        
        <a href="{{ route('admin.member-security') }}" class="menu-link {{ request()->routeIs('admin.member-security*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-user-lock"></i></span>
            <span class="menu-text">Member Security</span>
        </a>
        <a href="{{ url('admin/wallet-address') }}"
            class="menu-link {{ request()->routeIs('admin/wallet-address') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-wallet"></i></span>
            <span class="menu-text">Wallet Address</span>
        </a>
        
        <a href="{{ url('/admin/poolmember') }}"
            class="menu-link {{ request()->routeIs('admin/poolmember') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-people-group"></i></span>
            <span class="menu-text">Global Pool Member</span>
        </a>
        
        <a href="{{ url('/admin/weekly-count') }}"
            class="menu-link {{ request()->routeIs('admin/weekly-count') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fa-solid fa-calendar-week"></i></span>
            <span class="menu-text">Weekly Count Members</span>
        </a>
        
         <details class="sidebar-dropdown" {{ request()->routeIs('admin.deposits.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-id-card"></i></span>
                <span class="menu-text">KYC Section</span>
                <span class="menu-arrow">></span>
            </summary>

            <div class="sidebar-submenu">
                <a href="{{ url('admin/new-kyc-requests') }}"> <i
                        class="fa-solid fa-circle-plus"></i><span>{{ __('New KYC Requests') }}</span></a>
                <a href="{{ url('admin/verified-kyc-requests') }}"> <i
                        class="fa-solid fa-circle-plus"></i><span>{{ __('Verified KYCs') }}</span></a>
            </div>
        </details>

        {{-- <details class="sidebar-dropdown" {{ request()->routeIs('admin.deposits.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-coins"></i></span>
                <span class="menu-text">Deposit</span>
                <span class="menu-arrow">></span>
            </summary>

            <div class="sidebar-submenu">
                <a href="{{ route('admin.deposits.settings') }}" class="{{ request()->routeIs('admin.deposits.settings') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Deposit Settings</span>
                </a>
                <a href="{{ route('admin.deposits.index') }}" class="{{ request()->routeIs('admin.deposits.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Deposits</span>
                </a>
                <a href="{{ route('admin.deposits.reports') }}" class="{{ request()->routeIs('admin.deposits.reports') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Deposit Reports</span>
                </a>
            </div>
        </details> --}}

        {{-- <details class="sidebar-dropdown" {{ request()->routeIs('admin.activations.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.activations.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="menu-text">Activation</span>
                <span class="menu-arrow">></span>
            </summary>

            <div class="sidebar-submenu">
                <a href="{{ route('admin.activations.index') }}" class="{{ request()->routeIs('admin.activations.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Activations</span>
                </a>
                <a href="{{ route('admin.activations.upgrades') }}" class="{{ request()->routeIs('admin.activations.upgrades') ? 'active' : '' }}">
                    <i class="fa-solid fa-arrow-up-right-dots"></i>
                    <span>Upgrades</span>
                </a>
                <a href="{{ route('admin.activations.package-history') }}" class="{{ request()->routeIs('admin.activations.package-history') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Package History</span>
                </a>
            </div>
        </details> --}}

         <details class="sidebar-dropdown" {{ request()->routeIs('admin.investments.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.investments.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="menu-text">Investment</span>
                <span class="menu-arrow">></span>
            </summary>

            <div class="sidebar-submenu">
                <a href="{{ route('admin.investments.index') }}"
                    class="{{ request()->routeIs('admin.investments.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Investments Details</span>
                </a>
                <a href="{{ route('admin.incomes.staking') }}"
                    class="{{ request()->routeIs('admin.incomes.staking') ? 'active' : '' }}">
                    <i class="fa-solid fa-coins"></i>
                    <span>Daily Income</span>
                </a>
                <a href="{{ route('admin.incomes.roi') }}"
                    class="{{ request()->routeIs('admin.incomes.roi') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>ROI Distribution</span>
                </a>
            </div>
        </details>

        <details class="sidebar-dropdown" {{ request()->routeIs('admin.incomes.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.incomes.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                <span class="menu-text">Income</span>
                <span class="menu-arrow">></span>
            </summary>

            <div class="sidebar-submenu">
                {{-- <a href="{{ route('admin.incomes.community') }}" class="{{ request()->routeIs('admin.incomes.community') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Community Income</span>
                </a> --}}
                <a href="{{ route('admin.incomes.rank') }}"
                    class="{{ request()->routeIs('admin.incomes.rank') ? 'active' : '' }}">
                    <i class="fa-solid fa-ranking-star"></i>
                    <span>Rank Income</span>
                </a>
                
                <a href="{{ route('admin.incomes.rank.manual-transfer') }}"
                    class="{{ request()->routeIs('admin.incomes.rank.manual-transfer*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    <span>Rank Income - Manual Transfer</span>
                </a>

                <a href="{{ route('admin.incomes.direct') }}"
                    class="{{ request()->routeIs('admin.incomes.direct') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Referral Income</span>
                </a>

                <a href="{{ route('admin.incomes.level') }}"
                    class="{{ request()->routeIs('admin.incomes.level') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Level Income</span>
                </a>
               <a href="{{ route('admin.incomes.global-rec') }}"
                    class="{{ request()->routeIs('admin.incomes.global-rec') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>Global Rec Income</span>
                </a>
                <a href="{{ route('admin.incomes.global-rec-star') }}"
                    class="{{ request()->routeIs('admin.incomes.global-rec-star') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>Global Rec Star Income</span>
                </a>

            </div>
        </details>

        {{-- <details class="sidebar-dropdown" {{ request()->routeIs('admin.p2p.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.p2p.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <span class="menu-text">P2P</span>
                <span class="menu-arrow">></span>
            </summary>

            <p class="sidebar-submenu-heading">P2P MANAGEMENT</p>

            <div class="sidebar-submenu">
                <a href="{{ route('admin.p2p.member-wallets') }}" class="{{ request()->routeIs('admin.p2p.member-wallets') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Member Wallets</span>
                </a>
                <a href="{{ route('admin.p2p.wallet-ledger') }}" class="{{ request()->routeIs('admin.p2p.wallet-ledger') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Wallet Ledger</span>
                </a>
                <a href="{{ route('admin.p2p.wallet-adjustments') }}" class="{{ request()->routeIs('admin.p2p.wallet-adjustments') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Wallet Adjustments</span>
                </a>
                <a href="{{ route('admin.p2p.wallet-reports') }}" class="{{ request()->routeIs('admin.p2p.wallet-reports') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Wallet Reports</span>
                </a>
                <a href="{{ route('admin.p2p.wallet-audit-logs') }}" class="{{ request()->routeIs('admin.p2p.wallet-audit-logs') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Wallet Audit Logs</span>
                </a>
            </div>
        </details> --}}

        <!--<details class="sidebar-dropdown" {{ request()->routeIs('admin.payment.*') ? 'open' : '' }}>-->
        <!--    <summary class="menu-link {{ request()->routeIs('admin.payment.*') ? 'active' : '' }}">-->
        <!--        <span class="menu-icon"><i class="fa-solid fa-credit-card"></i></span>-->
        <!--        <span class="menu-text">Payment Management</span>-->
        <!--        <span class="menu-arrow">></span>-->
        <!--    </summary>-->

        <!--    <div class="sidebar-submenu">-->
        <!--        <a href="{{ route('admin.payment.requests.new') }}" class="{{ request()->routeIs('admin.payment.requests.new') ? 'active' : '' }}">-->
        <!--            <i class="fa-solid fa-hourglass-half"></i>-->
        <!--            <span>New Withdrawal Requests</span>-->
        <!--        </a>-->
        <!--        <a href="{{ route('admin.payment.requests.cancelled') }}" class="{{ request()->routeIs('admin.payment.requests.cancelled') ? 'active' : '' }}">-->
        <!--            <i class="fa-solid fa-circle-xmark"></i>-->
        <!--            <span>Cancelled Requests</span>-->
        <!--        </a>-->
        <!--        <a href="{{ route('admin.payment.history') }}" class="{{ request()->routeIs('admin.payment.history') ? 'active' : '' }}">-->
        <!--            <i class="fa-solid fa-clock-rotate-left"></i>-->
        <!--            <span>Payment History</span>-->
        <!--        </a>-->
        <!--    </div>-->
        <!--</details>-->

        <details class="sidebar-dropdown" {{ request()->routeIs('admin.withdrawals.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-money-bill-trend-up"></i></span>
                <span class="menu-text">WITHDRAWAL</span>
                <span class="menu-arrow">></span>
            </summary>

            <p class="sidebar-submenu-heading">WITHDRAWAL &amp; PAYOUTS</p>

            <div class="sidebar-submenu">
                <a href="{{ route('admin.withdrawals.requests') }}" class="{{ request()->routeIs('admin.withdrawals.requests') ? 'active' : '' }}">
                    <i class="fa-solid fa-hourglass-half"></i>
                    <span>Withdrawal Requests</span>
                </a>
                <a href="{{ route('admin.withdrawals.approved') }}" class="{{ request()->routeIs('admin.withdrawals.approved') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Approved Withdrawals</span>
                </a>
                <a href="{{ route('admin.withdrawals.cancelled') }}" class="{{ request()->routeIs('admin.withdrawals.cancelled') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <span>Cancelled Withdrawals</span>
                </a>
                <!--<a href="{{ route('admin.withdrawals.reports') }}" class="{{ request()->routeIs('admin.withdrawals.reports') ? 'active' : '' }}">-->
                <!--    <i class="fa-solid fa-chart-simple"></i>-->
                <!--    <span>Withdrawal Reports</span>-->
                <!--</a>-->
                <!--<a href="{{ route('admin.withdrawals.private-keys') }}" class="{{ request()->routeIs('admin.withdrawals.private-keys') ? 'active' : '' }}">-->
                <!--    <i class="fa-solid fa-key"></i>-->
                <!--    <span>Private Key Management</span>-->
                <!--</a>-->
            </div>
        </details>

        <details class="sidebar-dropdown" {{ request()->routeIs('admin.support.*') ? 'open' : '' }}>
            <summary class="menu-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="fa-solid fa-headset"></i></span>
                <span class="menu-text">SUPPORT</span>
                <span class="menu-arrow">></span>
            </summary>

            <p class="sidebar-submenu-heading">SUPPORT QUEUES</p>

            <div class="sidebar-submenu">
                 <a href="{{ url('/admin/support/new-support-tickets') }}"
                    class="{{ request()->routeIs('admin.support.new-support-tickets') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>New Tickets</span>
                </a>
                <a href="{{ route('admin.support.open') }}" class="{{ request()->routeIs('admin.support.open') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Open Tickets</span>
                </a>
                <a href="{{ route('admin.support.closed') }}" class="{{ request()->routeIs('admin.support.closed') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder"></i>
                    <span>Closed Tickets</span>
                </a>
            </div>
        </details>

        <p class="menu-label">System</p>

        <a href="{{ route('admin.count') }}" class="menu-link {{ request()->routeIs('admin.count') ? 'active' : '' }}">
            <span class="menu-icon">S</span>
            <span class="menu-text">Set Global Weekly Count</span>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="menu-link menu-button">
                <span class="menu-icon">L</span>
                <span class="menu-text">Logout</span>
            </button>
        </form>
    </nav>
</aside>
