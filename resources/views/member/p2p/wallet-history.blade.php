@extends('member.layouts.app')

@section('title', 'P2P Wallet History - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'P2P Wallet History')

@section('content')
<main class="member-page dashboard-page p2p-history-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @php
                $rows = $passdata ?? $history ?? $walletHistory ?? $p2pData ?? $data_list ?? $transferData ?? [];
                $rowsCollection = collect($rows);
                $totalDebit = $rowsCollection->sum(fn($row) => (float)($row->debit ?? 0));
                $totalCredit = $rowsCollection->sum(fn($row) => (float)($row->credit ?? 0));
                $latestP2PBalance = $data->p2p_wallet ?? 0;
            @endphp

            <!-- HERO STATS BANNER -->
            <article class="p2p-history-hero-stats-wrapper" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="p2p-history-mini-card glass-surface" style="padding: 16px; border-radius: 16px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(130,226,63,0.1); display: grid; place-items: center; color: var(--gl-green);">
                        <i class="fa-solid fa-arrow-down" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <small style="font-size: 11px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Total Received (Debit)</small>
                        <strong style="display: block; font-size: 18px; color: var(--gl-green); font-weight: 800;">$ {{ number_format($totalDebit, 2) }}</strong>
                    </div>
                </div>
                <div class="p2p-history-mini-card glass-surface" style="padding: 16px; border-radius: 16px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.1); display: grid; place-items: center; color: #ef4444;">
                        <i class="fa-solid fa-arrow-up" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <small style="font-size: 11px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Total Sent (Credit)</small>
                        <strong style="display: block; font-size: 18px; color: #ef4444; font-weight: 800;">$ {{ number_format($totalCredit, 2) }}</strong>
                    </div>
                </div>
                <div class="p2p-history-mini-card glass-surface" style="padding: 16px; border-radius: 16px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(6,182,212,0.1); display: grid; place-items: center; color: #06b6d4;">
                        <i class="fa-solid fa-wallet" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <small style="font-size: 11px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Current P2P Balance</small>
                        <strong style="display: block; font-size: 18px; color: #06b6d4; font-weight: 800;">$ {{ number_format($latestP2PBalance, 2) }}</strong>
                    </div>
                </div>
            </article>

            <!-- GLASS TABLE CARD -->
            <article class="p2p-history-card glass-surface">
                <div class="p2p-history-card-head" style="padding: 20px 24px; border-bottom: 1px solid var(--gl-border); display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="p2p-history-title" style="font-size: 18px; font-weight: 800; margin: 0; color: var(--gl-gold);">P2P Wallet History</h2>
                </div>

                <!-- TABLE TOOLBAR -->
                <div class="p2p-history-toolbar" style="padding: 16px 24px; border-bottom: 1px solid var(--gl-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div class="p2p-history-show">
                        <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                            Show 
                            <select name="entries" class="p2p-history-show-select" aria-label="Select entries number" style="background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text); padding: 4px 8px; border-radius: 6px; outline: none;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> 
                            entries
                        </label>
                    </div>
                    <div class="p2p-history-search">
                        <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                            Search:
                            <input type="search" class="p2p-history-search-input" placeholder="Search history..." aria-label="Search history" style="background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text); padding: 6px 12px; border-radius: 8px; outline: none; margin-left: 8px;">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="p2p-history-table-wrap">
                    <table class="p2p-history-table">
                        <thead>
                            <tr>
                                <th class="p2p-history-sort sort-asc" data-column="0" style="cursor: pointer; padding: 12px 16px; text-align: left;">S.No. <i class="fa-solid fa-sort-up sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="1" style="cursor: pointer; padding: 12px 16px; text-align: left;">Date <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="2" style="cursor: pointer; padding: 12px 16px; text-align: left;">Member ID <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="3" style="cursor: pointer; padding: 12px 16px; text-align: left;">Amount <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="4" style="cursor: pointer; padding: 12px 16px; text-align: left;">Type <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="5" style="cursor: pointer; padding: 12px 16px; text-align: left;">Wallet Type / Mode <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="6" style="cursor: pointer; padding: 12px 16px; text-align: left;">Remarks <i class="fa-solid fa-sort sort-icon"></i></th>
                                <th class="p2p-history-sort" data-column="7" style="cursor: pointer; padding: 12px 16px; text-align: left;">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $index => $row)
                                <tr>
                                    <td style="padding: 12px 16px;">{{ $index + 1 }}</td>
                                    <td style="padding: 12px 16px;" data-sort-val="{{ Carbon\Carbon::parse($row->created_at ?? '')->timestamp ?? 0 }}">{{ $row->created_at ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-weight: 700; color: var(--gl-gold);">{{ $row->memberid ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-weight: 700;" data-sort-val="{{ (float)(($row->debit ?? 0) > 0 ? $row->debit : $row->credit) }}">
                                        @if ((float)($row->debit ?? 0) > 0)
                                            <span style="color: var(--gl-green);">+${{ number_format($row->debit, 2) }}</span>
                                        @else
                                            <span style="color: #ef4444;">-${{ number_format($row->credit, 2) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px;">
                                        @if ((float)($row->debit ?? 0) > 0)
                                            <span class="p2p-history-badge p2p-history-type-debit" style="background: rgba(130,226,63,0.12); color: var(--gl-green); padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700;">Debit (Received)</span>
                                        @else
                                            <span class="p2p-history-badge p2p-history-type-credit" style="background: rgba(239,68,68,0.12); color: #ef4444; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700;">Credit (Sent)</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; font-weight: 700;">{{ $row->walletType ?? '' }}</td>
                                    <td style="padding: 12px 16px; color: var(--gl-muted);">{{ $row->particular ?? $row->remark ?? '' }}</td>
                                    <td style="padding: 12px 16px;">
                                        <span style="background: rgba(130,226,63,0.12); color: var(--gl-green); padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700;">Success</span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="8" class="p2p-history-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 24px;">No P2P wallet history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION WRAPPER -->
                <div class="income-pagination-info" style="padding: 16px 24px; border-top: 1px solid var(--gl-border); font-size: 13px; color: var(--gl-muted); font-weight: 600; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <span id="js-p2p-pagination-summary">Showing 0 to 0 of 0 entries</span>
                    <div class="income-pagination-buttons" style="display: flex; gap: 8px;">
                        <button type="button" class="pill-button" id="js-p2p-prev-btn" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px; cursor: pointer; background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text);">Previous</button>
                        <button type="button" class="pill-button" id="js-p2p-next-btn" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px; cursor: pointer; background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text);">Next</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
