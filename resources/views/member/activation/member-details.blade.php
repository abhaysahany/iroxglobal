@extends('member.layouts.app')

@section('title', 'Member Details - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Member Details')

@section('content')
<main class="member-page dashboard-page activation-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content activation-container">
            @php
                $detailsCollection = collect($memberDetails ?? []);
                $totalRecords = $detailsCollection->count();
                $activeCount = $detailsCollection->filter(fn($row) => strtolower(data_get($row, 'status', '')) === 'active')->count();
                $pendingCount = $detailsCollection->filter(fn($row) => strtolower(data_get($row, 'status', '')) === 'pending')->count();
            @endphp

            <!-- HERO BANNER -->
            <article class="page-hero-card glass-surface activation-hero">
                <div class="page-hero-content">
                    <div class="page-hero-left">
                        <span class="page-hero-pill activation-pill">ACTIVATION MEMBERS</span>
                        <h1 class="page-hero-title activation-title">Member Details</h1>
                        <p class="page-hero-desc activation-desc">View member activation details and package information.</p>
                        <div class="page-hero-meta activation-meta">
                            <span class="page-hero-meta-pill">Section: Activation</span>
                            <span class="page-hero-meta-pill">Records: {{ $totalRecords }}</span>
                        </div>
                    </div>
                    <div class="page-hero-stats activation-stats">
                        <div class="page-hero-stat glass-surface activation-stat-card">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-user-check" style="color: var(--gl-green);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Active Members</span>
                                <strong class="page-hero-stat-value">{{ $activeCount }}</strong>
                            </div>
                        </div>
                        <div class="page-hero-stat glass-surface activation-stat-card">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-users" style="color: var(--gl-gold);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Package Members</span>
                                <strong class="page-hero-stat-value">{{ $totalRecords }}</strong>
                            </div>
                        </div>
                        <div class="page-hero-stat glass-surface activation-stat-card">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-user-clock" style="color: var(--gl-muted);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Pending Members</span>
                                <strong class="page-hero-stat-value">{{ $pendingCount }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Table Card -->
            <article class="activation-table-card glass-surface">
                <div class="activation-table-header" style="padding: 18px 24px; border-bottom: 1px solid var(--gl-border);">
                    <h2 class="activation-title" style="font-size: 18px; font-weight: 700; margin: 0;">Member Activation Details</h2>
                </div>

                <!-- TABLE TOOLBAR -->
                <div class="activation-table-toolbar">
                    <div class="activation-show">
                        <label>
                            Show 
                            <select name="entries" class="activation-show-select" aria-label="Select number of entries to show">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> 
                            entries
                        </label>
                    </div>
                    <div class="activation-search">
                        <label>
                            Search:
                            <input type="search" class="activation-search-input" placeholder="Search..." aria-label="Search records">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL ON MOBILE -->
                <div class="activation-table-wrap">
                    <table class="activation-table">
                        <thead>
                            <tr>
                                <th class="activation-sort sort-asc" data-column="0" style="cursor: pointer;">S.No. <i class="fa-solid fa-sort-up sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="1" style="cursor: pointer;">Date <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="2" style="cursor: pointer;">Member ID <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="3" style="cursor: pointer;">Member Name <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="4" style="cursor: pointer;">Package <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="5" style="cursor: pointer;">Year <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="6" style="cursor: pointer;">Registration Amount <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="7" style="cursor: pointer;">Total Amount <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                                <th class="activation-sort" data-column="8" style="cursor: pointer;">Status <i class="fa-solid fa-sort sort-icon" style="margin-left: 6px; font-size: 11px; color: var(--gl-muted);"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($memberDetails as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td data-sort-val="{{ isset($row['date']) ? Carbon\Carbon::parse($row['date'])->timestamp : 0 }}">{{ $row['date'] }}</td>
                                    <td>{{ $row['member_id'] }}</td>
                                    <td>{{ $row['member_name'] }}</td>
                                    <td>{{ $row['package'] }}</td>
                                    <td>{{ $row['year'] }}</td>
                                    <td data-sort-val="{{ (float)($row['registration_amount'] ?? 0) }}">${{ number_format($row['registration_amount'] ?? 0, 2) }}</td>
                                    <td data-sort-val="{{ (float)($row['total_amount'] ?? 0) }}">${{ number_format($row['total_amount'] ?? 0, 2) }}</td>
                                    <td data-sort-val="{{ strtolower($row['status'] ?? 'pending') }}">
                                        @php
                                            $status = strtolower($row['status'] ?? 'pending');
                                        @endphp
                                        @if ($status === 'active')
                                            <span class="activation-status activation-status-active">Active</span>
                                        @elseif ($status === 'pending')
                                            <span class="activation-status activation-status-pending">Pending</span>
                                        @elseif ($status === 'rejected')
                                            <span class="activation-status activation-status-rejected">Rejected</span>
                                        @else
                                            <span class="activation-status">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="activation-empty">
                                    <td colspan="9" style="text-align: center; padding: 32px; color: var(--gl-muted); font-weight: 600;">No data available in table</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="activation-pagination-info" style="margin-top: 14px; padding: 0 24px 24px 24px; font-size: 13px; color: var(--gl-muted); font-weight: 600; display: flex; justify-content: space-between; align-items: center;">
                    <span id="js-pagination-summary">Showing 0 to 0 of 0 entries</span>
                    <div class="activation-pagination-buttons" style="display: flex; gap: 8px;">
                        <button type="button" class="pill-button" id="js-prev-page-btn" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Previous</button>
                        <button type="button" class="pill-button" id="js-next-page-btn" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Next</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
