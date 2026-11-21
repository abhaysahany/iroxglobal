@extends('member.layouts.app')

@section('title', 'Direct Members - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Direct Members')

@section('content')
    <main class="member-page dashboard-page referral-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content">
                @php
                    $directMembersCollection = collect($directMembers ?? []);
                    $latestMemberId =
                        $directMembersCollection->count() > 0
                            ? data_get($directMembersCollection->last(), 'member_id', '-')
                            : '-';
                    $latestPackage =
                        $directMembersCollection->count() > 0
                            ? data_get($directMembersCollection->last(), 'joining_package', '-')
                            : '-';
                    $totalInvestment = $directMembersCollection->sum(
                        fn($row) => (float) data_get($row, 'investment', 0),
                    );
                    $activeCount = $directMembersCollection
                        ->filter(fn($row) => strtolower(data_get($row, 'status', '')) === 'active')
                        ->count();
                @endphp

                <!-- HERO SUMMARY CARD -->
                <article class="referral-hero glass-surface">
                    <div class="referral-hero-content">
                        <div class="referral-hero-left">
                            <span class="referral-pill">TEAM MANAGEMENT</span>
                            <h1 class="referral-title">Direct Members</h1>
                            <p class="referral-desc">View your direct team members with their joining details, activation
                                status, package information, and contact details.</p>
                            <div class="referral-meta">
                                <span class="referral-pill">Section: Team</span>
                                <span class="referral-pill">Records: {{ $directMembersCollection->count() }}</span>
                            </div>
                        </div>
                        <div class="referral-hero-right">
                            <div class="referral-stats">
                                <div class="referral-stat-card glass-surface">
                                    <i class="fa-solid fa-user-tag" style="color: var(--gl-gold);"></i>
                                    <div>
                                        <small>Member ID</small>
                                        <strong>{{ $latestMemberId }}</strong>
                                    </div>
                                </div>
                                <div class="referral-stat-card glass-surface">
                                    <i class="fa-solid fa-box" style="color: var(--gl-green);"></i>
                                    <div>
                                        <small>Package</small>
                                        <strong>{{ $latestPackage }}</strong>
                                    </div>
                                </div>
                                <div class="referral-stat-card glass-surface">
                                    <i class="fa-solid fa-circle-dollar-to-slot" style="color: #06b6d4;"></i>
                                    <div>
                                        <small>Investment</small>
                                        <strong>$ {{ number_format($totalInvestment, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="referral-stat-card glass-surface">
                                    <i class="fa-solid fa-circle-check" style="color: var(--gl-gold);"></i>
                                    <div>
                                        <small>Status</small>
                                        <strong>{{ $activeCount }} Active</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- DETAILS TABLE -->
                <article class="referral-table-card glass-surface"
                    style="margin-top: 24px; padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel);">
                    <div style="margin-bottom: 24px; border-bottom: 1px solid var(--gl-border); padding-bottom: 16px;">
                        <h2 class="referral-title" style="font-size: 18px; margin: 0; color: var(--gl-gold);">Direct Members
                            Details</h2>
                    </div>

                    <!-- TABLE TOOLBAR -->
                    <div class="referral-table-toolbar"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                        <div class="referral-table-show">
                            <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                                Show
                                <select name="entries" class="referral-show-select" aria-label="Select entries count"
                                    style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 700; outline: none; cursor: pointer;">
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
                                <input type="search" class="referral-search-input" placeholder="Search members..."
                                    aria-label="Search entries"
                                    style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 600; outline: none; margin-left: 8px; width: 200px; transition: border-color 0.2s;">
                            </label>
                        </div>
                    </div>

                    <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                    <div class="referral-table-wrap"
                        style="width: 100%; overflow-x: auto; border: 1px solid var(--gl-border); border-radius: 14px; background: var(--gl-panel-soft); box-shadow: inset 0 2px 8px var(--gl-shadow);">
                        <table class="referral-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr
                                    style="border-bottom: 2px solid var(--gl-border-strong); background: rgba(5, 15, 8, 0.4);">
                                    <th class="referral-sort sort-asc" data-column="0"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        S.No. <i class="fa-solid fa-sort-up sort-icon"
                                            style="font-size: 11px; margin-left: 6px;"></i></th>
                                    <th
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; user-select: none;">
                                        Image</th>
                                    <th class="referral-sort" data-column="2"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Joining Date <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="3"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Activation Date <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="4"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Member ID <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="5"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Name <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="6"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Mobile <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="7"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Email <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="8"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Rank <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="9"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Joining Package <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="10"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Investment <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                    <th class="referral-sort" data-column="11"
                                        style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">
                                        Status <i class="fa-solid fa-sort sort-icon"
                                            style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($directMembers as $index => $row)
                                    <tr style="border-bottom: 1px solid var(--gl-border);">
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $index + 1 }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            @if (!empty($row['image']))
                                                <img src="{{ asset($row['image']) }}" alt="{{ $row['name'] ?? 'Member' }}"
                                                    class="referral-avatar">
                                            @else
                                                @php
                                                    $words = explode(' ', $row['name'] ?? '');
                                                    $initials = '';
                                                    if (count($words) > 0) {
                                                        $initials .= strtoupper(substr($words[0], 0, 1));
                                                        if (count($words) > 1) {
                                                            $initials .= strtoupper(substr($words[1], 0, 1));
                                                        }
                                                    }
                                                    $initials = $initials ?: '?';
                                                @endphp
                                                <span class="referral-avatar-placeholder">{{ $initials }}</span>
                                            @endif
                                        </td>
                                        <td data-sort-val="{{ Carbon\Carbon::parse($row['joining_date'] ?? '')->timestamp ?? 0 }}"
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['joining_date'] ?? '' }}</td>
                                        <td data-sort-val="{{ Carbon\Carbon::parse($row['activation_date'] ?? '')->timestamp ?? 0 }}"
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['activation_date'] ?? '' }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['member_id'] ?? '' }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['name'] ?? '' }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['mobile'] ?? '' }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            @if (!empty($row['email']))
                                                <a href="mailto:{{ $row['email'] }}"
                                                    style="color: var(--gl-green); text-decoration: none;">{{ $row['email'] }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['rank'] ?? '' }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            {{ $row['joining_package'] ?? '' }}</td>
                                        <td data-sort-val="{{ (float) ($row['investment'] ?? 0) }}"
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            ${{ number_format($row['investment'] ?? 0, 2) }}</td>
                                        <td
                                            style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                            @php
                                                $status = strtolower($row['status'] ?? '');
                                            @endphp
                                            <span class="referral-status"
                                                style="font-size: 9.5px; padding: 2px 8px; border-radius: 20px; border: 1px solid currentColor; font-weight: 800; letter-spacing: 0.3px;
                                            @if ($status === 'active') background: rgba(73,133,36,0.08); color: var(--gl-green);
                                            @elseif($status === 'inactive') background: rgba(255,255,255,0.08); color: var(--gl-muted);
                                            @elseif($status === 'pending') background: rgba(212,175,55,0.08); color: var(--gl-gold);
                                            @elseif($status === 'blocked') background: rgba(220,53,69,0.08); color: var(--gl-danger);
                                            @else background: rgba(255,255,255,0.08); color: var(--gl-text); @endif">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="js-empty-row">
                                        <td colspan="12" class="referral-empty"
                                            style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">
                                            No data available in table</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION WRAPPER -->
                    <div class="referral-table-pagination"
                        style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 13px; color: var(--gl-muted); font-weight: 600;">
                        <span class="referral-table-pagination-summary">Showing 0 to 0 of 0 entries</span>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="referral-table-btn referral-table-prev-btn pill-button"
                                style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Previous</button>
                            <button type="button" class="referral-table-btn referral-table-next-btn pill-button"
                                style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Next</button>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </main>
@endsection
