@extends('member.layouts.app')
@section('title', 'Level Members - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Level Members')
@section('content')

<style>
    .referral-page .referral-table th,
    .referral-page .referral-table td {
        width: auto !important;
    }

    .referral-page .referral-table th:last-child,
    .referral-page .referral-table td:last-child {
        width: 150px !important;
        max-width: 150px !important;
        text-align: center !important;
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .referral-page .referral-action-btn {
        display: inline-flex !important;
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
        min-height: 30px !important;
        padding: 0 14px !important;
        color: #fff !important;
        background: linear-gradient(135deg, var(--gl-green), var(--gl-green-dark)) !important;
        border-color: var(--gl-green) !important;
        border-radius: 8px;
        letter-spacing: 0;
        text-transform: none;
        white-space: nowrap;
        box-shadow: none !important;
    }

    .referral-page .referral-action-btn:hover {
        color: #fff !important;
        background: linear-gradient(135deg, var(--gl-green-dark), var(--gl-green)) !important;
        border-color: var(--gl-green-dark) !important;
    }
</style>
<main class="member-page dashboard-page referral-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @php
                $levelMembersCollection = collect($levelMembers ?? []);
                $firstLevelNo = $levelMembersCollection->count() > 0 ? data_get($levelMembersCollection->first(), 'level_no', '-') : '-';
                $totalMembersCount = $levelMembersCollection->sum(fn($row) => (int)data_get($row, 'total_members', 0));
            @endphp

            <!-- HERO SUMMARY CARD -->
            <article class="referral-hero glass-surface">
                <div class="referral-hero-content">
                    <div class="referral-hero-left">
                        <span class="referral-pill">TEAM STRUCTURE</span>
                        <h1 class="referral-title">Level Members</h1>
                        <p class="referral-desc">View all members of each level in your team hierarchy. Click on View to see detailed information about members at each level.</p>
                        <div class="referral-meta">
                            <span class="referral-pill">Section: Team</span>
                            <span class="referral-pill">Levels: 1-20</span>
                        </div>
                    </div>
                    <div class="referral-hero-right">
                        <div class="referral-stats">
                            <div class="referral-stat-card glass-surface" style="grid-column: span 2;">
                                <i class="fa-solid fa-layer-group" style="color: var(--gl-gold);"></i>
                                <div>
                                    <small>Level No</small>
                                    <strong>{{ $firstLevelNo }}</strong>
                                </div>
                            </div>
                            <div class="referral-stat-card glass-surface">
                                <i class="fa-solid fa-users" style="color: var(--gl-green);"></i>
                                <div>
                                    <small>Total Members</small>
                                    <strong>{{ number_format($totalMembersCount) }}</strong>
                                </div>
                            </div>
                            <div class="referral-stat-card glass-surface">
                                <i class="fa-solid fa-eye" style="color: #06b6d4;"></i>
                                <div>
                                    <small>Action</small>
                                    <strong>View</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- DETAILS TABLE -->
            <article class="referral-table-card glass-surface" style="margin-top: 24px; padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel);">
                <div style="margin-bottom: 24px; border-bottom: 1px solid var(--gl-border); padding-bottom: 16px;">
                    <h2 class="referral-title" style="font-size: 18px; margin: 0; color: var(--gl-gold);">Level Member Table</h2>
                </div>

                <!-- TABLE TOOLBAR -->
                <div class="referral-table-toolbar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                    <div class="referral-table-show">
                        <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                            Show 
                            <select name="entries" class="referral-show-select" aria-label="Select entries count" style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 700; outline: none; cursor: pointer;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> 
                            entries
                        </label>
                    </div>
                    <div class="referral-table-search">
                        <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                            Search:
                            <input type="search" class="referral-search-input" placeholder="Search levels..." aria-label="Search entries" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 600; outline: none; margin-left: 8px; width: 200px; transition: border-color 0.2s;">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="referral-table-wrap" style="width: 100%; overflow-x: auto; border: 1px solid var(--gl-border); border-radius: 14px; background: var(--gl-panel-soft); box-shadow: inset 0 2px 8px var(--gl-shadow);">
                    <table class="referral-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <colgroup>
                            <col style="width: 16%;">
                            <col style="width: 22%;">
                            <col style="width: 22%;">
                            <col style="width: 22%;">
                            <col style="width: 150px;">
                        </colgroup>
                        <thead>
                            <tr style="border-bottom: 2px solid var(--gl-border-strong); background: rgba(5, 15, 8, 0.4);">
                                <th class="referral-sort sort-asc" data-column="0" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">S.No. <i class="fa-solid fa-sort-up sort-icon" style="font-size: 11px; margin-left: 6px;"></i></th>
                                <th class="referral-sort" data-column="1" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Level No <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="2" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Total Members <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="3" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Business <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; user-select: none;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($levelMembers as $index => $row)
                                <tr style="border-bottom: 1px solid var(--gl-border);">
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $index + 1 }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['level_no'] ?? '' }}</td>
                                    <td data-sort-val="{{ (int)($row['total_members'] ?? 0) }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['total_members'] ?? 0 }}</td>
                                    <td data-sort-val="{{ (float)($row['business'] ?? 0) }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">${{ number_format($row['business'] ?? 0, 2) }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                        <a href="javascript:void(0)" class="btn referral-action-btn" style="display: inline-flex !important; width: 120px !important; min-width: 120px !important; max-width: 120px !important; min-height: 30px !important; padding: 0 12px !important; align-items: center !important; justify-content: center !important; color: #fff !important; background: linear-gradient(135deg, var(--gl-green), var(--gl-green-dark)) !important; border: 1px solid var(--gl-green) !important; border-radius: 8px !important; font-size: 12px !important; font-weight: 700 !important; text-decoration: none !important; white-space: nowrap !important;">
                                            {{ $row['action'] ?? 'View' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="5" class="referral-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">No data available in table</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION WRAPPER -->
                <div class="referral-table-pagination" style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 13px; color: var(--gl-muted); font-weight: 600;">
                    <span class="referral-table-pagination-summary">Showing 0 to 0 of 0 entries</span>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="referral-table-btn referral-table-prev-btn pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Previous</button>
                        <button type="button" class="referral-table-btn referral-table-next-btn pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Next</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
