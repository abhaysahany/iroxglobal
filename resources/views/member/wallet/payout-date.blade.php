@extends('member.layouts.app')

@section('title', 'Payout Date - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Payout Date')

@section('content')
<main class="member-page dashboard-page wallet-table-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @php
                $payoutsCollection = collect($payoutDates ?? []);
                $upcomingPayouts = $payoutsCollection->filter(fn($row) => in_array(strtolower(data_get($row, 'status', '')), ['pending', 'scheduled']))->count();
                $totalNetPayout = $payoutsCollection->sum(fn($row) => (float)data_get($row, 'net_amount', 0));
                $latestPayoutRow = $payoutsCollection->last();
                $lastPayoutDate = $latestPayoutRow ? data_get($latestPayoutRow, 'payout_date', '-') : '-';
            @endphp

            <!-- HERO BANNER -->
            <article class="page-hero-card glass-surface">
                <div class="page-hero-content">
                    <div class="page-hero-left">
                        <span class="page-hero-pill">PAYOUT SCHEDULE</span>
                        <h1 class="page-hero-title">Payout Date</h1>
                        <p class="page-hero-desc">View payout cycle details, scheduled payout dates, payment mode, and payout status.</p>
                        <div class="page-hero-meta">
                            <span class="page-hero-meta-pill">SECTION: WALLET</span>
                            <span class="page-hero-meta-pill">RECORDS: {{ $payoutsCollection->count() }}</span>
                        </div>
                    </div>
                    <div class="page-hero-stats">
                        <div class="page-hero-stat glass-surface">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-calendar-days" style="color: var(--gl-gold);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Upcoming Payouts</span>
                                <strong class="page-hero-stat-value">{{ $upcomingPayouts }}</strong>
                            </div>
                        </div>
                        <div class="page-hero-stat glass-surface">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-dollar-sign" style="color: var(--gl-green);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Net Amount</span>
                                <strong class="page-hero-stat-value">$ {{ number_format($totalNetPayout, 2) }}</strong>
                            </div>
                        </div>
                        <div class="page-hero-stat glass-surface">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-clock" style="color: #06b6d4;"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Last Payout Date</span>
                                <strong class="page-hero-stat-value">{{ $lastPayoutDate }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- GLASS TABLE CARD -->
            <article class="wallet-table-card glass-surface">
                <div class="wallet-table-card-head">
                    <h2 class="wallet-table-title" style="font-size: 18px; margin: 0;">Payout Date Details</h2>
                </div>

                <!-- TABLE TOOLBAR -->
                <div class="wallet-table-toolbar">
                    <div class="wallet-table-show">
                        <label>
                            Show 
                            <select name="entries" class="wallet-table-show-select" aria-label="Select entries count">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> 
                            entries
                        </label>
                    </div>
                    <div class="wallet-table-search">
                        <label>
                            Search:
                            <input type="search" class="wallet-table-search-input" placeholder="Search payout dates..." aria-label="Search entries">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="wallet-table-wrap">
                    <table class="wallet-table">
                        <thead>
                            <tr>
                                <th class="wallet-table-sort sort-asc" data-column="0" style="cursor: pointer;">S.No. <i class="fa-solid fa-sort-up sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="1" style="cursor: pointer;">Payout Date <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="2" style="cursor: pointer;">Payout Cycle <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="3" style="cursor: pointer;">Member Id <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="4" style="cursor: pointer;">Member Name <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="5" style="cursor: pointer;">Gross Amount <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="6" style="cursor: pointer;">Admin Charges <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="7" style="cursor: pointer;">Net Amount <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="8" style="cursor: pointer;">Payment Mode <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="9" style="cursor: pointer;">Wallet Address <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="10" style="cursor: pointer;">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="11" style="cursor: pointer;">Remarks <i class="fa-solid fa-sort sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payoutDates as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($row['payout_date'] ?? '')->timestamp ?? 0 }}">{{ $row['payout_date'] ?? '' }}</td>
                                    <td>{{ $row['payout_cycle'] ?? '' }}</td>
                                    <td>{{ $row['member_id'] ?? '' }}</td>
                                    <td>{{ $row['member_name'] ?? '' }}</td>
                                    <td data-sort-val="{{ (float)($row['gross_amount'] ?? 0) }}">${{ number_format($row['gross_amount'] ?? 0, 2) }}</td>
                                    <td data-sort-val="{{ (float)($row['admin_charges'] ?? 0) }}">${{ number_format($row['admin_charges'] ?? 0, 2) }}</td>
                                    <td data-sort-val="{{ (float)($row['net_amount'] ?? 0) }}">${{ number_format($row['net_amount'] ?? 0, 2) }}</td>
                                    <td>{{ $row['payment_mode'] ?? '' }}</td>
                                    <td>{{ $row['wallet_address'] ?? '' }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($row['status'] ?? 'pending');
                                        @endphp
                                        @if ($status === 'approved' || $status === 'paid' || $status === 'completed')
                                            <span class="wallet-table-badge wallet-table-status-approved">{{ ucfirst($status) }}</span>
                                        @elseif ($status === 'rejected' || $status === 'failed')
                                            <span class="wallet-table-badge wallet-table-status-rejected">{{ ucfirst($status) }}</span>
                                        @elseif ($status === 'hold')
                                            <span class="wallet-table-badge wallet-table-status-hold">{{ ucfirst($status) }}</span>
                                        @elseif ($status === 'processing')
                                            <span class="wallet-table-badge wallet-table-status-processing">{{ ucfirst($status) }}</span>
                                        @else
                                            <span class="wallet-table-badge wallet-table-status-pending">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['remarks'] ?? '' }}</td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="12" class="wallet-table-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">No data available in table</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION WRAPPER -->
                <div class="wallet-table-pagination" style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 13px; color: var(--gl-muted); font-weight: 600;">
                    <span class="wallet-table-pagination-summary">Showing 0 to 0 of 0 entries</span>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="wallet-table-btn wallet-table-prev-btn pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Previous</button>
                        <button type="button" class="wallet-table-btn wallet-table-next-btn pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Next</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
