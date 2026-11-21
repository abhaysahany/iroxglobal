@extends('member.layouts.app')

@section('title', 'Wallet To P2P History - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Wallet to P2P History')

@section('content')
<main class="member-page dashboard-page wallet-table-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @php
                $totalCredit = 0;
                $totalDebit = 0;
                foreach ($walletToP2PHistory ?? [] as $row) {
                    $totalCredit += (float)($row['credit'] ?? 0);
                    $totalDebit += (float)($row['debit'] ?? 0);
                }
                $currentP2PBalance = session('member_p2p_wallet_balance', 0);
                $lastRecord = count($walletToP2PHistory ?? []) > 0 ? $walletToP2PHistory[count($walletToP2PHistory) - 1] : null;
                $lastParticular = $lastRecord['particular'] ?? '-';
                $lastAmount = $lastRecord ? ($lastRecord['credit'] > 0 ? $lastRecord['credit'] : $lastRecord['debit']) : 0;
            @endphp

            <!-- HERO BANNER -->
            <article class="wallet-table-hero glass-surface">
                <div class="wallet-table-hero-content">
                    <div class="wallet-table-hero-left">
                        <span class="wallet-table-pill">TRANSACTION HISTORY</span>
                        <h1 class="wallet-table-title">Wallet to P2P History</h1>
                        <p class="wallet-table-desc">View your wallet to P2P transfer history with transaction details, amounts, and balance information.</p>
                        <div class="wallet-table-hero-tags">
                            <span class="wallet-table-pill">Section: Wallet</span>
                            <span class="wallet-table-pill">Records: {{ count($walletToP2PHistory ?? []) }}</span>
                        </div>
                    </div>
                    <div class="wallet-table-hero-right">
                        <div class="wallet-table-hero-stats">
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-receipt" style="color: var(--gl-gold);"></i>
                                <div>
                                    <small>Last Activity</small>
                                    <strong style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width: 140px; display: block;" title="{{ $lastParticular }}">{{ $lastParticular }}</strong>
                                </div>
                            </div>
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-dollar-sign" style="color: var(--gl-green);"></i>
                                <div>
                                    <small>Last Transferred</small>
                                    <strong class="wallet-table-credit">$ {{ number_format($lastAmount, 2) }}</strong>
                                </div>
                            </div>
                            <div class="wallet-table-mini-card glass-surface">
                                <i class="fa-solid fa-arrow-right-arrow-left" style="color: #06b6d4;"></i>
                                <div>
                                    <small>P2P Wallet Balance</small>
                                    <strong class="wallet-table-balance">$ {{ number_format($currentP2PBalance, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- GLASS TABLE CARD -->
            <article class="wallet-table-card glass-surface" style="margin-top: 24px;">
                <div class="wallet-table-card-head">
                    <h2 class="wallet-table-title" style="font-size: 18px; margin: 0;">Wallet To P2P History</h2>
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
                            <input type="search" class="wallet-table-search-input" placeholder="Search transfer history..." aria-label="Search entries">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="wallet-table-wrap">
                    <table class="wallet-table">
                        <thead>
                            <tr>
                                <th class="wallet-table-sort sort-asc" data-column="0" style="cursor: pointer;">S.No. <i class="fa-solid fa-sort-up sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="1" style="cursor: pointer;">Date <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="2" style="cursor: pointer;">Particular <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="3" style="cursor: pointer;">Credit <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="4" style="cursor: pointer;">Debit <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="wallet-table-sort" data-column="5" style="cursor: pointer;">Balance <i class="fa-solid fa-sort sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($walletToP2PHistory as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($row['date'] ?? '')->timestamp ?? 0 }}">{{ $row['date'] ?? '' }}</td>
                                    <td>{{ $row['particular'] ?? '' }}</td>
                                    <td data-sort-val="{{ (float)($row['credit'] ?? 0) }}" class="wallet-table-credit">
                                        @if ((float)($row['credit'] ?? 0) > 0)
                                            +${{ number_format($row['credit'], 2) }}
                                        @else
                                            $0.00
                                        @endif
                                    </td>
                                    <td data-sort-val="{{ (float)($row['debit'] ?? 0) }}" class="wallet-table-debit">
                                        @if ((float)($row['debit'] ?? 0) > 0)
                                            -${{ number_format($row['debit'], 2) }}
                                        @else
                                            $0.00
                                        @endif
                                    </td>
                                    <td data-sort-val="{{ (float)($row['balance'] ?? 0) }}">${{ number_format($row['balance'] ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="6" class="wallet-table-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">No data available in table</td>
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
