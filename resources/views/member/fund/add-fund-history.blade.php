@extends('member.layouts.app')

@section('title', 'Add Fund History - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Add Fund History')

@section('content')
<main class="member-page dashboard-page fund-history-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content fund-history-container">
            @php
                $requestsCollection = collect($addfundData ?? []);
                $totalRequests = $requestsCollection->count();
                $pendingRequests = $requestsCollection->filter(fn($row) => strtolower(data_get($row, 'status', '')) === 'pending' || data_get($row, 'status') == '0' || data_get($row, 'status') === null)->count();
                $approvedAmount = $requestsCollection->filter(fn($row) => strtolower(data_get($row, 'status', '')) === 'approved' || data_get($row, 'status') == '1')->sum(fn($row) => (float)data_get($row, 'amount', 0));
            @endphp

            <!-- HERO BANNER -->
            <article class="page-hero-card glass-surface">
                <div class="page-hero-content">
                    <div class="page-hero-left">
                        <span class="page-hero-pill">FUND REQUESTS</span>
                        <h1 class="page-hero-title">Add Fund History</h1>
                        <p class="page-hero-desc">Track your add fund requests with transaction ID, amount, type, and approval status.</p>
                        <div class="page-hero-meta">
                            <span class="page-hero-meta-pill">SECTION: ADD FUND</span>
                            <span class="page-hero-meta-pill">RECORDS: {{ $totalRequests }}</span>
                        </div>
                    </div>
                    <div class="page-hero-stats">
                        <div class="page-hero-stat glass-surface">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-list-check" style="color: var(--gl-gold);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Total Requests</span>
                                <strong class="page-hero-stat-value">{{ $totalRequests }}</strong>
                            </div>
                        </div>
                        <!--<div class="page-hero-stat glass-surface">-->
                        <!--    <div class="page-hero-stat-icon">-->
                        <!--        <i class="fa-solid fa-clock" style="color: var(--gl-muted);"></i>-->
                        <!--    </div>-->
                        <!--    <div class="page-hero-stat-text">-->
                        <!--        <span class="page-hero-stat-label">Pending Requests</span>-->
                        <!--        <strong class="page-hero-stat-value">{{ $pendingRequests }}</strong>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="page-hero-stat glass-surface">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-circle-check" style="color: var(--gl-green);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Approved Amount</span>
                                <strong class="page-hero-stat-value">$ {{ number_format($approvedAmount, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Glass Table Card -->
            <article class="fund-history-card glass-surface">
                <div class="fund-history-card-header">
                    <h2 class="fund-history-title">Add Fund Request History Table</h2>
                </div>

                <!-- TABLE TOOLBAR -->
                <div class="fund-history-toolbar">
                    <div class="fund-history-show">
                        <label>
                            Show 
                            <select name="entries" class="fund-history-show-select" aria-label="Select number of entries to show">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> 
                            entries
                        </label>
                    </div>
                    <div class="fund-history-search">
                        <label>
                            Search:
                            <input type="search" class="fund-history-search-input" placeholder="Search..." aria-label="Search history records">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL ON MOBILE -->
                <div class="fund-history-table-wrap">
                    <table class="fund-history-table">
                        <thead>
                            <tr>
                                <th class="fund-history-sort sort-asc" data-column="0">S.No. <i class="fa-solid fa-sort-up sort-icon"></i></th>
                                <th class="fund-history-sort" data-column="1">Date <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="fund-history-sort" data-column="2">Memberid <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="fund-history-sort" data-column="3">Txn Id <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="fund-history-sort" data-column="4">Amount <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="fund-history-sort" data-column="5">Type <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="fund-history-sort" data-column="6">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addfundData ?? [] as $index => $request)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($request->created_at ?? $request['created_at'] ?? '')->timestamp ?? 0 }}">
                                        {{ $request->created_at ?? $request['created_at'] ?? '-' }}
                                    </td>
                                    <td>{{ $request->memberid ?? $request['memberid'] ?? '-' }}</td>
                                    <td><span class="txn-id-badge">{{ $request->txnid ?? $request['txnid'] ?? '-' }}</span></td>
                                    <td data-sort-val="{{ (float)($request->amount ?? $request['amount'] ?? 0) }}">
                                        @if(is_numeric($request->amount ?? $request['amount'] ?? null))
                                            ${{ number_format($request->amount ?? $request['amount'], 2) }}
                                        @else
                                            {{ $request->amount ?? $request['amount'] ?? '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $request->type ?? $request['type'] ?? 'Add' }}</td>
                                    <td>
                                        @php
                                            $statusVal = strtolower($request->status ?? $request['status'] ?? 'pending');
                                        @endphp
                                        @if ($statusVal === 'approved' || $statusVal == '1')
                                            <span class="fund-history-status approved">Approved</span>
                                        @elseif ($statusVal === 'rejected' || $statusVal == '2' || $statusVal === 'cancelled')
                                            <span class="fund-history-status rejected">{{ ucfirst($request->status ?? $request['status'] ?? 'Rejected') }}</span>
                                        @else
                                            <span class="fund-history-status pending">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="7" class="fund-history-empty">No data available in table</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="fund-history-pagination-info" style="margin-top: 14px; font-size: 13px; color: var(--gl-muted); font-weight: 600; display: flex; justify-content: space-between; align-items: center;">
                    <span id="js-pagination-summary">Showing 0 to 0 of 0 entries</span>
                    <div class="fund-history-pagination-buttons" style="display: flex; gap: 8px;">
                        <button type="button" class="pill-button" id="js-prev-page-btn" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Previous</button>
                        <button type="button" class="pill-button" id="js-next-page-btn" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Next</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
