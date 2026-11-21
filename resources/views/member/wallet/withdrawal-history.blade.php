@extends('member.layouts.app')

@section('title', 'Withdrawal History - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Withdrawal History')

@section('content')
<main class="member-page dashboard-page wallet-table-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @if (session('successMsg'))
                <div class="wallet-alert wallet-alert-success" style="margin-bottom: 15px;">{{ session('successMsg') }}</div>
            @endif

            @if (session('failedMsg'))
                <div class="wallet-alert wallet-alert-error" style="margin-bottom: 15px;">{{ session('failedMsg') }}</div>
            @endif

            @if ($errors->any())
                <div class="wallet-alert wallet-alert-error" style="margin-bottom: 15px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @php
                $lastRecord = count($withdrawalHistory ?? []) > 0 ? $withdrawalHistory[0] : null;
                $lastRequestId = $lastRecord['request_id'] ?? '-';
                $totalGross = 0;
                $totalCharges = 0;
                $totalNet = 0;
                foreach ($withdrawalHistory ?? [] as $row) {
                    $totalGross += (float)($row['withdrawn_amount'] ?? 0);
                    $totalCharges += (float)($row['admin_charges'] ?? 0);
                    $totalNet += (float)($row['final_payout'] ?? 0);
                }
            @endphp

            <!-- HERO BANNER -->
            <article class="wallet-table-hero glass-surface">
                <div class="wallet-table-hero-content">
                    <div class="wallet-table-hero-left">
                        <span class="wallet-table-pill">TRANSACTION HISTORY</span>
                        <h1 class="wallet-table-title">Withdrawal History</h1>
                        <p class="wallet-table-desc">Track your withdrawal requests with request ID, wallet address, amounts, and payment status.</p>
                        <div class="wallet-table-hero-tags">
                            <span class="wallet-table-pill">Section: Wallet</span>
                            <span class="wallet-table-pill">Records: {{ count($withdrawalHistory ?? []) }}</span>
                        </div>
                    </div>
                    <div class="wallet-table-hero-right">
                        <div class="wallet-table-hero-stats">
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-hashtag" style="color: var(--gl-gold);"></i>
                                <div>
                                    <small>Last Request Id</small>
                                    <strong>{{ $lastRequestId }}</strong>
                                </div>
                            </div>
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-dollar-sign" style="color: var(--gl-green);"></i>
                                <div>
                                    <small>Total Gross Amount</small>
                                    <strong class="wallet-table-credit">$ {{ number_format($totalGross, 2) }}</strong>
                                </div>
                            </div>
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-percent" style="color: var(--gl-danger);"></i>
                                <div>
                                    <small>Total Charges</small>
                                    <strong class="wallet-table-debit">$ {{ number_format($totalCharges, 2) }}</strong>
                                </div>
                            </div>
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-hand-holding-dollar" style="color: #06b6d4;"></i>
                                <div>
                                    <small>Total Net Amount</small>
                                    <strong class="wallet-table-balance">$ {{ number_format($totalNet, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- GLASS TABLE CARD -->
            <article class="wallet-table-card glass-surface" style="margin-top: 24px;">
                <div class="wallet-table-card-head">
                    <h2 class="wallet-table-title" style="font-size: 18px; margin: 0;">Withdrawal History Details</h2>
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
                            <input type="search" class="wallet-table-search-input" placeholder="Search withdrawal history..." aria-label="Search entries">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="wallet-table-wrap">
                    <table class="wallet-table">
                        <thead>
                            <tr>
                                <th class="wallet-table-sort sort-asc" data-column="0" style="cursor: pointer;">S.No. <i class="fa-solid fa-sort-up sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="1" style="cursor: pointer;">Request Date <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="2" style="cursor: pointer;">Request Id <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="3" style="cursor: pointer;">Payment Date <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="4" style="cursor: pointer;">Member Id <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="5" style="cursor: pointer;">Member Name <i class="fa-solid fa-sort sort-icon"></i></th>
                                <!--<th class="wallet-table-sort" data-column="6" style="cursor: pointer;">Contact No <i class="fa-solid fa-sort sort-icon"></i></th>-->
                                <th class="wallet-table-sort" data-column="7" style="cursor: pointer;">Email <i class="fa-solid fa-sort sort-icon"></i></th>
                                <!--<th class="wallet-table-sort" data-column="8" style="cursor: pointer;">Location <i class="fa-solid fa-sort sort-icon"></i></th>-->
                                <!--<th class="wallet-table-sort" data-column="9" style="cursor: pointer;">Sponsor ID <i class="fa-solid fa-sort sort-icon"></i></th>-->
                                <!--<th class="wallet-table-sort" data-column="10" style="cursor: pointer;">Sponsor Name <i class="fa-solid fa-sort sort-icon"></i></th>-->
                                <th class="wallet-table-sort" data-column="11" style="cursor: pointer;">Withdrawn Amount <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="12" style="cursor: pointer;">Admin Charges <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="13" style="cursor: pointer;">Final Payout <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="14" style="cursor: pointer;">Wallet Address <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="15" style="cursor: pointer;">Reason <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="16" style="cursor: pointer;">Amount Disbursed Mode <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="17" style="cursor: pointer;">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($withdrawalHistory as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($row['request_date'] ?? '')->timestamp ?? 0 }}">{{ $row['request_date'] ?? '' }}</td>
                                    <td>{{ $row['request_id'] ?? '' }}</td>
                                    <td data-sort-val="{{ $row['payment_date'] !== '-' && $row['payment_date'] ? Carbon\Carbon::parse($row['payment_date'])->timestamp : 0 }}">{{ $row['payment_date'] ?? '' }}</td>
                                    <td>{{ $row['member_id'] ?? '' }}</td>
                                    <td>{{ $row['member_name'] ?? '' }}</td>
                                    <!--<td>{{ $row['contact_no'] ?? '' }}</td>-->
                                    <td>{!! filter_var($row['email'] ?? '', FILTER_VALIDATE_EMAIL) ? '<a href="mailto:'.$row['email'].'" style="color: var(--gl-gold); text-decoration: underline;">'.$row['email'].'</a>' : ($row['email'] ?? '') !!}</td>
                                    <!--<td>{{ $row['location'] ?? '' }}</td>-->
                                    <!--<td>{{ $row['sponsor_id'] ?? '' }}</td>-->
                                    <!--<td>{{ $row['sponsor_name'] ?? '' }}</td>-->
                                    <td data-sort-val="{{ (float)($row['withdrawn_amount'] ?? 0) }}">${{ number_format($row['withdrawn_amount'] ?? 0, 2) }}</td>
                                    <td data-sort-val="{{ (float)($row['admin_charges'] ?? 0) }}">${{ number_format($row['admin_charges'] ?? 0, 2) }}</td>
                                    <td data-sort-val="{{ (float)($row['final_payout'] ?? 0) }}">${{ number_format($row['final_payout'] ?? 0, 2) }}</td>
                                    <td>{{ $row['wallet_address'] ?? '' }}</td>
                                    <td>{{ $row['reason'] ?? '' }}</td>
                                    <td>{{ $row['amount_disbursed_mode'] ?? '' }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($row['status'] ?? 'pending');
                                        @endphp
                                        @if ($status === 'approved' || $status === 'paid' || $status === 'completed')
                                            <span class="wallet-table-badge wallet-table-status-approved">{{ ucfirst($status) }}</span>
                                        @elseif ($status === 'rejected' || $status === 'failed' || $status === 'cancelled' || $status === 'canceled')
                                            <span class="wallet-table-badge wallet-table-status-rejected">{{ ucfirst($status) }}</span>
                                        @elseif ($status === 'hold')
                                            <span class="wallet-table-badge wallet-table-status-hold">{{ ucfirst($status) }}</span>
                                        @elseif ($status === 'processing')
                                            <span class="wallet-table-badge wallet-table-status-processing">{{ ucfirst($status) }}</span>
                                        @else
                                            <span class="wallet-table-badge wallet-table-status-pending">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="18" class="wallet-table-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">No data available in table</td>
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
