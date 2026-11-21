@extends('member.layouts.app')

@section('title', 'Genealogy Tree - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Genealogy Tree')

@section('content')
<main class="member-page dashboard-page referral-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @php
                $genealogyCollection = collect($genealogyRows ?? []);
                $latestPackage = $genealogyCollection->count() > 0 ? data_get($genealogyCollection->last(), 'package', '-') : '-';
                $directCount = $genealogyCollection->filter(fn($row) => strtolower(data_get($row, 'level_no', '')) === 'level 1')->count();
                $totalTeamBiz = $genealogyCollection->sum(fn($row) => (float)data_get($row, 'team_business', 0));
                $memberId = data_get($member, 'member_id', session('member.member_id', 'GLN-784512'));
            @endphp

            <!-- HERO SUMMARY CARD -->
            <article class="referral-hero glass-surface">
                <div class="referral-hero-content">
                    <div class="referral-hero-left">
                        <span class="referral-pill">TEAM STRUCTURE</span>
                        <h1 class="referral-title">Genealogy Tree</h1>
                        <p class="referral-desc">View your team genealogy tree with member details, sponsor information, package details, and wallet status.</p>
                        <div class="referral-meta">
                            <span class="referral-pill">Section: Team</span>
                            <span class="referral-pill">Records: {{ $genealogyCollection->count() }}</span>
                        </div>
                    </div>
                    <div class="referral-hero-right">
                        <div class="referral-stats">
                            <div class="referral-stat-card glass-surface">
                                <i class="fa-solid fa-user-tag" style="color: var(--gl-gold);"></i>
                                <div>
                                    <small>Member ID</small>
                                    <strong>{{ $memberId }}</strong>
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
                                <i class="fa-solid fa-users" style="color: #06b6d4;"></i>
                                <div>
                                    <small>Direct</small>
                                    <strong>{{ $directCount }}</strong>
                                </div>
                            </div>
                            <div class="referral-stat-card glass-surface">
                                <i class="fa-solid fa-chart-line" style="color: var(--gl-gold);"></i>
                                <div>
                                    <small>Team Biz</small>
                                    <strong>$ {{ number_format($totalTeamBiz, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- VISUAL GENEALOGY TREE SECTION -->
            <article class="referral-tree-card glass-surface" style="margin-top: 24px; padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel);">
                <div style="margin-bottom: 24px; border-bottom: 1px solid var(--gl-border); padding-bottom: 16px;">
                    <h2 class="referral-title" style="font-size: 18px; margin: 0; color: var(--gl-gold);">Genealogy Structure</h2>
                </div>

                @if ($genealogyCollection->count() > 0)
                    <div class="referral-tree-container" style="display: flex; flex-direction: column; align-items: center; margin: 20px 0;">
                        <!-- Root Member -->
                        <div class="referral-tree-root" style="display: flex; flex-direction: column; align-items: center; position: relative;">
                            <div class="referral-tree-node root-node" style="padding: 16px; border-radius: 12px; border: 2px solid var(--gl-gold); background: var(--gl-panel-soft); display: flex; flex-direction: column; align-items: center; min-width: 160px; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.15); text-align: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(212, 175, 55, 0.1); display: grid; place-items: center; margin-bottom: 8px;">
                                    <i class="fa-solid fa-crown" style="color: var(--gl-gold); font-size: 18px;"></i>
                                </div>
                                <strong style="font-size: 13.5px; color: var(--gl-gold);">{{ $memberId }}</strong>
                                <small style="font-size: 12px; color: var(--gl-text); font-weight: 700; margin-top: 2px;">{{ data_get($member, 'name', 'John Doe') }}</small>
                                <span class="referral-status" style="border: 1px solid var(--gl-gold); background: rgba(212, 175, 55, 0.05); color: var(--gl-gold); font-size: 9.5px; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; font-weight: 800; margin-top: 8px; letter-spacing: 0.5px;">Root</span>
                            </div>
                        </div>

                        <!-- Connector V-Line -->
                        <div class="referral-tree-line" style="width: 2px; height: 30px; background: var(--gl-border); margin: 0 auto; position: relative;"></div>

                        <!-- Downline Nodes -->
                        <div class="referral-tree-level" style="display: flex; justify-content: center; gap: 24px; width: 100%; flex-wrap: wrap; position: relative; padding-top: 10px;">
                            @php
                                $directs = $genealogyCollection->filter(fn($row) => strtolower(data_get($row, 'level_no', '')) === 'level 1');
                            @endphp
                            @forelse($directs as $direct)
                                <div class="referral-tree-node-wrapper" style="display: flex; flex-direction: column; align-items: center; position: relative; flex: 0 1 180px;">
                                    <div class="referral-tree-node" style="padding: 14px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft); display: flex; flex-direction: column; align-items: center; width: 100%; text-align: center; box-shadow: var(--gl-shadow); transition: transform 0.2s;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(73, 133, 36, 0.1); display: grid; place-items: center; margin-bottom: 8px;">
                                            <i class="fa-solid fa-user" style="color: var(--gl-green); font-size: 14px;"></i>
                                        </div>
                                        <strong style="font-size: 12.5px; color: var(--gl-text);">{{ data_get($direct, 'member_id', '-') }}</strong>
                                        <small style="font-size: 11px; color: var(--gl-muted); font-weight: 700; margin-top: 2px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width: 100%;">{{ data_get($direct, 'member_name', '-') }}</small>
                                        
                                        @php
                                            $status = strtolower(data_get($direct, 'status', ''));
                                        @endphp
                                        <span class="referral-status" style="font-size: 9px; padding: 1px 6px; border-radius: 20px; border: 1px solid currentColor; margin-top: 6px; font-weight: 800; letter-spacing: 0.3px;
                                            @if($status === 'active') background: rgba(73,133,36,0.08); color: var(--gl-green);
                                            @else background: rgba(220,53,69,0.08); color: var(--gl-danger); @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="referral-empty" style="font-size: 13px; font-style: italic; color: var(--gl-muted);">No direct downlines found in Level 1</div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="referral-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 40px; font-weight: 700;">No genealogy records found</div>
                @endif
            </article>

            <!-- DETAILS TABLE -->
            <article class="referral-table-card glass-surface" style="margin-top: 24px; padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel);">
                <div style="margin-bottom: 24px; border-bottom: 1px solid var(--gl-border); padding-bottom: 16px;">
                    <h2 class="referral-title" style="font-size: 18px; margin: 0; color: var(--gl-gold);">Genealogy Details</h2>
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
                            <input type="search" class="referral-search-input" placeholder="Search members..." aria-label="Search entries" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 600; outline: none; margin-left: 8px; width: 200px; transition: border-color 0.2s;">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="referral-table-wrap" style="width: 100%; overflow-x: auto; border: 1px solid var(--gl-border); border-radius: 14px; background: var(--gl-panel-soft); box-shadow: inset 0 2px 8px var(--gl-shadow);">
                    <table class="referral-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--gl-border-strong); background: rgba(5, 15, 8, 0.4);">
                                <th class="referral-sort sort-asc" data-column="0" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">S.No. <i class="fa-solid fa-sort-up sort-icon" style="font-size: 11px; margin-left: 6px;"></i></th>
                                <th class="referral-sort" data-column="1" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Level No <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="2" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Member ID <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="3" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Member Name <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="4" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Sponsor ID <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="5" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Sponsor Name <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="6" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Package <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="7" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Rank <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="8" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Direct <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="9" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Team Biz <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="10" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Wallet Status <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="11" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Joining Date <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="12" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Activation Date <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="referral-sort" data-column="13" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Status <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($genealogyRows as $index => $row)
                                <tr style="border-bottom: 1px solid var(--gl-border);">
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $index + 1 }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['level_no'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['member_id'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['member_name'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['sponsor_id'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['sponsor_name'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['package'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['rank'] ?? '' }}</td>
                                    <td data-sort-val="{{ (int)($row['direct_count'] ?? 0) }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['direct_count'] ?? 0 }}</td>
                                    <td data-sort-val="{{ (float)($row['team_business'] ?? 0) }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">${{ number_format($row['team_business'] ?? 0, 2) }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                        @php
                                            $walletStatus = strtolower($row['wallet_status'] ?? '');
                                        @endphp
                                        <span class="referral-status" style="font-size: 9.5px; padding: 2px 8px; border-radius: 20px; border: 1px solid currentColor; font-weight: 800; letter-spacing: 0.3px;
                                            @if($walletStatus === 'active') background: rgba(73,133,36,0.08); color: var(--gl-green);
                                            @else background: rgba(220,53,69,0.08); color: var(--gl-danger); @endif">
                                            {{ ucfirst($walletStatus) }}
                                        </span>
                                    </td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($row['joining_date'] ?? '')->timestamp ?? 0 }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['joining_date'] ?? '' }}</td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($row['activation_date'] ?? '')->timestamp ?? 0 }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row['activation_date'] ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                        @php
                                            $status = strtolower($row['status'] ?? '');
                                        @endphp
                                        <span class="referral-status" style="font-size: 9.5px; padding: 2px 8px; border-radius: 20px; border: 1px solid currentColor; font-weight: 800; letter-spacing: 0.3px;
                                            @if($status === 'active') background: rgba(73,133,36,0.08); color: var(--gl-green);
                                            @else background: rgba(220,53,69,0.08); color: var(--gl-danger); @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="14" class="referral-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">No data available in table</td>
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
