@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

    <style>
        .dashboard-stat-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 22px;
            width: 100%;
        }

        /* Default cards: 4 cards in one row */
        .dashboard-stat-grid>.dashboard-stat-card {
            grid-column: span 3;
        }

        /* 2 cards in one row */
        .dashboard-stat-grid>.dashboard-stat-col-6 {
            grid-column: span 6;
        }

        /* 3 cards in one row */
        .dashboard-stat-grid>.dashboard-stat-col-4 {
            grid-column: span 4;
        }

        .dashboard-stat-card {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            min-height: 138px;
            height: auto;

            background: #ffffff;
            border-radius: 16px;
            padding: 22px 24px;
            box-sizing: border-box;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .dashboard-stat-content {
            display: flex;
            align-items: center;
            gap: 18px;
            min-width: 0;
        }

        .dashboard-stat-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            background: #eee7ff;
            color: #7c5ce6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
        }

        .dashboard-stat-content p {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 700;
            color: #566176;
            line-height: 1.3;
        }

        .dashboard-stat-content h2 {
            margin: 0 0 8px;
            font-size: 28px;
            font-weight: 800;
            color: #020617;
            line-height: 1;
        }

        .dashboard-stat-content small {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
        }

        .growth.success {
            color: #00b341;
        }

        .growth.warning {
            color: #ff9800;
        }

        .mini-line {
            display: flex;
            align-items: flex-end;
            gap: 7px;
            height: 48px;
            min-width: 82px;
        }

        .mini-line span {
            width: 12px;
            border-radius: 8px;
            background: #ded2fb;
            display: block;
        }

        .mini-line span:nth-child(1) {
            height: 18px;
        }

        .mini-line span:nth-child(2) {
            height: 26px;
        }

        .mini-line span:nth-child(3) {
            height: 34px;
        }

        .mini-line span:nth-child(4) {
            height: 48px;
        }

        .mini-line span:nth-child(5) {
            height: 32px;
        }

        @media (max-width: 1200px) {
            .dashboard-stat-grid>.dashboard-stat-card {
                grid-column: span 6;
            }
        }

        @media (max-width: 576px) {

            .dashboard-stat-grid>.dashboard-stat-card,
            .dashboard-stat-grid>.dashboard-stat-col-6,
            .dashboard-stat-grid>.dashboard-stat-col-4 {
                grid-column: span 12;
            }
        }

        /* Action Card Styles */
        .action-card {
            text-decoration: none !important;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(15, 23, 42, 0.05) !important;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.12) !important;
        }

        .action-card-main {
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 1;
        }

        .action-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #566176;
            margin: 0;
        }

        .action-card-value {
            font-size: 32px;
            font-weight: 800;
            color: #020617;
            margin: 0;
            line-height: 1.1;
        }

        .action-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            width: fit-content;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-card-badge.pulse-badge {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            animation: badgePulse 2s infinite alternate;
        }

        .action-card-badge.success-badge {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .action-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 16px;
            font-size: 22px;
            z-index: 1;
            transition: all 0.3s ease;
        }

        /* Color Accents */
        .action-card.accent-orange {
            background: linear-gradient(135deg, #ffffff, #fff7ed) !important;
        }

        .action-card.accent-orange .action-card-icon {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
        }

        .action-card.accent-orange:hover {
            border-color: rgba(249, 115, 22, 0.3) !important;
        }

        .action-card.accent-orange:hover .action-card-icon {
            background: #f97316;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }

        .action-card.accent-blue-alt {
            background: linear-gradient(135deg, #ffffff, #f0f9ff) !important;
        }

        .action-card.accent-blue-alt .action-card-icon {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .action-card.accent-blue-alt:hover {
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        .action-card.accent-blue-alt:hover .action-card-icon {
            background: #3b82f6;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        /* Dark Mode overrides */
        body.theme-dark .action-card {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        body.theme-dark .action-card:hover {
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        body.theme-dark .action-card.accent-orange {
            background: linear-gradient(135deg, rgba(30, 21, 10, 0.4), rgba(20, 15, 10, 0.3)) !important;
        }

        body.theme-dark .action-card.accent-blue-alt {
            background: linear-gradient(135deg, rgba(10, 18, 30, 0.4), rgba(10, 13, 20, 0.3)) !important;
        }

        body.theme-dark .action-card-title {
            color: #94a3b8;
        }

        body.theme-dark .action-card-value {
            color: #f8fafc;
        }

        @keyframes badgePulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            100% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
        }

        /* Clickable Stats Cards */
        .clickable-card {
            text-decoration: none !important;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.12) !important;
        }
    </style>

    <div class="page-header dashboard-page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Dashboard</h1>
        </div>
        <div class="breadcrumb">
            <span>Home</span>
            <span>/</span>
            <strong>Dashboard</strong>
        </div>
    </div>

    <section class="dashboard-stat-grid">

        <a href="{{ route('admin.members.index') }}" class="dashboard-stat-card clickable-card accent-purple">
            <div class="dashboard-stat-content">
                <span class="dashboard-stat-icon" aria-hidden="true">
                    <!-- User icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <div>
                    <p>Total Members</p>
                    <h2>{{ totalMembersadmin() }}</h2>
                    <small class="growth success">All Members</small>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.members.index', ['state' => 'active']) }}"
            class="dashboard-stat-card clickable-card accent-blue">
            <div class="dashboard-stat-content">
                <span class="dashboard-stat-icon" aria-hidden="true">
                    <!-- Check / active icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </span>
                <div>
                    <p>Active Members</p>
                    <h2>{{ totalActiveMembers() }}</h2>
                    <small class="growth warning">Active Members</small>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.members.index', ['state' => 'temp']) }}"
            class="dashboard-stat-card clickable-card accent-green">
            <div class="dashboard-stat-content">
                <span class="dashboard-stat-icon" aria-hidden="true">
                    <!-- Clock / temp icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 3"></path>
                    </svg>
                </span>
                <div>
                    <p>Temp Members</p>
                    <h2>{{ totalTempMembers() }}</h2>
                    <small class="growth success">Temp Members</small>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.members.index', ['state' => 'blocked']) }}"
            class="dashboard-stat-card clickable-card accent-orange">
            <div class="dashboard-stat-content">
                <span class="dashboard-stat-icon" aria-hidden="true">
                    <!-- Lock / blocked icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <div>
                    <p>Blocked Members</p>
                    <h2>{{ totalblockedMembers() }}</h2>
                    <small class="growth warning">Blocked Members</small>
                </div>
            </div>
        </a>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-6">
            <div class="dashboard-stat-content">
                {{-- <span class="dashboard-stat-icon">TW</span> --}}
                <div>
                    <p>Total Main Wallet</p>
                    <h2>$ {{ number_format(totalWallet(), 2) }}</h2>
                    <small class="growth warning">Total Main Wallet Records</small>
                </div>
            </div>
            <div class="mini-icon" aria-hidden="true"
                style="display:flex;align-items:center;justify-content:center;min-width:82px;height:48px;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#7c5ce6" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3"></path>
                    <path d="M22 12v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7"></path>
                    <circle cx="16" cy="13" r="1"></circle>
                </svg>
            </div>
        </div>

        <div class="dashboard-stat-card accent-orange dashboard-stat-col-6">
            <div class="dashboard-stat-content">
                <div>
                    <p>Total P2P Wallet</p>
                    <h2>$ {{ number_format(totalP2PWallet(), 2) }}</h2>
                    <small class="growth warning">Total P2P Wallet Records</small>
                </div>
            </div>
            <div class="mini-icon" aria-hidden="true"
                style="display:flex;align-items:center;justify-content:center;min-width:82px;height:48px;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#7c5ce6" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 7h18a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H2V7z"></path>
                    <path d="M16 12a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
                    <path d="M2 11V9a2 2 0 0 1 2-2h14"></path>
                </svg>
            </div>
        </div>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-6">
            <div class="dashboard-stat-content">
                <div>
                    <p>Total Deposit</p>
                    <h2>$ {{ number_format(totalDeposit(), 2) }}</h2>
                    <small class="growth success">Total Deposit Records</small>
                </div>
            </div>
            <div class="mini-icon" aria-hidden="true"
                style="display:flex;align-items:center;justify-content:center;min-width:82px;height:48px;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#7c5ce6"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                    <polyline points="7 10 12 15 17 10"></polyline>
                </svg>
            </div>
        </div>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-6">
            <div class="dashboard-stat-content">
                <div>
                    <p>Total Withdrawal</p>
                    <h2>$ {{ number_format(totalWithdrawal(), 2) }}</h2>
                    <small class="growth success">Total Withdrawal Records</small>
                </div>
            </div>
            <div class="mini-icon" aria-hidden="true"
                style="display:flex;align-items:center;justify-content:center;min-width:82px;height:48px;">
                <!-- Withdrawal icon: wallet with down arrow -->
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#7c5ce6"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="7" width="20" height="12" rx="2"></rect>
                    <circle cx="7" cy="13" r="1"></circle>
                    <path d="M12 3v8"></path>
                    <path d="M8 9l4 4 4-4"></path>
                </svg>
            </div>
        </div>

        <div class="dashboard-stat-card"
            style="grid-column: span 12; margin-bottom: 2px; display:flex; align-items:center; gap:16px; background:#ffffff; padding:16px; min-height: 45px;">
            <div>
                <h3 style="margin:0;font-size:18px;font-weight:800;color:#020617;">Total Income Overview</h3>
            </div>
            <div style="margin-left:auto;">
                <h2 style="margin:0;font-size:28px;font-weight:800;color:#020617;">$
                    {{ number_format(totalIncomes(), 2) }}</h2>
            </div>
        </div>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-4">
            <div class="dashboard-stat-content">
                {{-- <span class="dashboard-stat-icon">DI</span> --}}
                <div>
                    <p>Daily Income</p>
                    <h2>$ {{ number_format(totalDailyIncome(), 2) }}</h2>
                    <small class="growth success">Total Daily Income Records</small>
                </div>
            </div>
            <div class="mini-line"><span></span><span></span><span></span><span></span><span></span></div>
        </div>

        <div class="dashboard-stat-card accent-orange dashboard-stat-col-4">
            <div class="dashboard-stat-content">
                {{-- <span class="dashboard-stat-icon">RI</span> --}}
                <div>
                    <p>Referral Income</p>
                    <h2>$ {{ number_format(totalReferralIncome(), 2) }}</h2>
                    <small class="growth warning">Total Refferal Income Records</small>
                </div>
            </div>
            <div class="mini-line"><span></span><span></span><span></span><span></span><span></span></div>
        </div>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-4">
            <div class="dashboard-stat-content">
                {{-- <span class="dashboard-stat-icon">RI</span> --}}
                <div>
                    <p>Rank Income</p>
                    <h2>$ {{ number_format(totalRankIncome(), 2) }}</h2>
                    <small class="growth success">Total Rank Income Records</small>
                </div>
            </div>
            <div class="mini-line"><span></span><span></span><span></span><span></span><span></span></div>
        </div>

        <div class="dashboard-stat-card accent-orange dashboard-stat-col-6">
            <div class="dashboard-stat-content">
                {{-- <span class="dashboard-stat-icon">GRI</span> --}}
                <div>
                    <p>Global Rac Income</p>
                    <h2>$ {{ number_format(totalGlobalRacIncome(), 2) }}</h2>
                    <small class="growth warning">Total Global Rac Records</small>
                </div>
            </div>
            <div class="mini-line"><span></span><span></span><span></span><span></span><span></span></div>
        </div>

        <div class="dashboard-stat-card accent-orange dashboard-stat-col-6">
            <div class="dashboard-stat-content">
                {{-- <span class="dashboard-stat-icon">GRSI</span> --}}
                <div>
                    <p>Global Rac Star Income</p>
                    <h2>$ {{ number_format(totalGlobalRacStarIncome(), 2) }}</h2>
                    <small class="growth warning">Total Global Rac Star records</small>
                </div>
            </div>
            <div class="mini-line"><span></span><span></span><span></span><span></span><span></span></div>
        </div>

        <a href="{{ url('/admin/withdrawals/requests') }}"
            class="dashboard-stat-card action-card accent-orange dashboard-stat-col-4">
            <div class="dashboard-stat-content" style="width: 100%; justify-content: space-between; gap: 15px;">
                <div class="action-card-main">
                    <p class="action-card-title">New Withdrawal Requests</p>
                    <h2 class="action-card-value">{{ newWithdrawalRequest() }}</h2>
                    @if (newWithdrawalRequest() > 0)
                        <span class="action-card-badge pulse-badge">Requires Attention</span>
                    @else
                        <span class="action-card-badge success-badge"><i class="fa-solid fa-circle-check"></i> All
                            Cleared</span>
                    @endif
                </div>
                <div class="action-card-icon">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
            </div>
        </a>
        <a href="{{ url('/admin/new-kyc-requests') }}"
            class="dashboard-stat-card action-card accent-orange dashboard-stat-col-4">
            <div class="dashboard-stat-content" style="width: 100%; justify-content: space-between; gap: 15px;">
                <div class="action-card-main">
                    <p class="action-card-title">New KYC Requests</p>
                    <h2 class="action-card-value">{{ newKycRequests() }}</h2>
                    @if (newKycRequests() > 0)
                        <span class="action-card-badge pulse-badge">Requires Attention</span>
                    @else
                        <span class="action-card-badge success-badge"><i class="fa-solid fa-circle-check"></i> All
                            Cleared</span>
                    @endif
                </div>
                <div class="action-card-icon">
                    <i class="fa-solid fa-id-card"></i>
                </div>
            </div>
        </a>

        <a href="{{ url('/admin/support/new-support-tickets') }}"
            class="dashboard-stat-card action-card accent-blue-alt dashboard-stat-col-4">
            <div class="dashboard-stat-content" style="width: 100%; justify-content: space-between; gap: 15px;">
                <div class="action-card-main">
                    <p class="action-card-title">New Support Tickets</p>
                    <h2 class="action-card-value">{{ newSupportTicket() }}</h2>
                    @if (newSupportTicket() > 0)
                        <span class="action-card-badge pulse-badge">New Messages</span>
                    @else
                        <span class="action-card-badge success-badge"><i class="fa-solid fa-circle-check"></i> No Pending
                            Tickets</span>
                    @endif
                </div>
                <div class="action-card-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
            </div>
        </a>


        <div class="dashboard-stat-card accent-purple dashboard-stat-col-4">
            <div class="dashboard-stat-content">
                <div style="width:100%">
                    <p>Withdrawal Gross Amount</p>
                    <h2>$ {{ number_format(totalgrossAmount(), 2) }}</h2>

                    <!-- Progress strip demo -->
                    <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                        <div style="flex:1;background:#f3f1ff;border-radius:999px;height:8px;overflow:hidden;">
                            <div style="width:78%;height:100%;background:#7c5ce6;border-radius:999px;" role="progressbar"
                                aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small style="min-width:40px;text-align:right;font-weight:700;color:#566176"></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-4">
            <div class="dashboard-stat-content">
                <div style="width:100%">
                    <p>Withdrawal Net Amount</p>
                    <h2>$ {{ number_format(totalnetAmount(), 2) }}</h2>

                    <!-- Progress strip demo -->
                    <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                        <div style="flex:1;background:#eef9f1;border-radius:999px;height:8px;overflow:hidden;">
                            <div style="width:52%;height:100%;background:#00b341;border-radius:999px;" role="progressbar"
                                aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small style="min-width:40px;text-align:right;font-weight:700;color:#566176"></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-stat-card accent-purple dashboard-stat-col-4">
            <div class="dashboard-stat-content">
                <div style="width:100%">
                    <p>Deduction on Withdrawal</p>
                    <h2>$ {{ number_format(totaldeductions(), 2) }}</h2>

                    <!-- Progress strip demo -->
                    <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                        <div style="flex:1;background:#fff6ef;border-radius:999px;height:8px;overflow:hidden;">
                            <div style="width:25%;height:100%;background:#ff9800;border-radius:999px;" role="progressbar"
                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small style="min-width:40px;text-align:right;font-weight:700;color:#566176"></small>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
