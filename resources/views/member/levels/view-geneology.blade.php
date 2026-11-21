@extends('member.layouts.app')

@section('title', 'Genealogy Tree - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Genealogy Tree')

@section('content')
<main class="member-page dashboard-page referral-page genealogy-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @if (session('failedMsg'))
                <div class="alert alert-danger" style="background: rgba(220,53,69,0.15); border: 1px solid var(--gl-danger); color: #f87171; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i>
                    {{ session('failedMsg') }}
                </div>
            @endif

            <!-- REFERRAL LINK CARD & SUMMARY -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px;">
                <!-- Referral Link Card -->
                <article class="referral-hero glass-surface" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                    <div>
                        <span class="referral-pill">MY REFERRAL LINK</span>
                        <h2 class="referral-title" style="font-size: 20px; margin-top: 8px; color: var(--gl-gold);">Invite Partners</h2>
                        <p class="referral-desc" style="font-size: 13px; margin: 8px 0 16px 0;">Share your personal referral link to build your team and earn direct/level rewards.</p>
                        
                        <div style="display: flex; gap: 8px; background: rgba(5,15,8,0.4); border: 1px solid var(--gl-border); padding: 8px 12px; border-radius: 8px; align-items: center;">
                            <input type="text" id="refLinkInput" value="{{ route('member.signup', ['id' => $data->memberid]) }}" readonly style="background: transparent; border: none; color: var(--gl-text); font-size: 12.5px; width: 100%; outline: none; font-weight: 700;">
                            <button type="button" id="copyRefBtn" class="pill-button" style="min-height: 28px; padding: 0 12px; font-size: 11.5px; font-weight: 700; white-space: nowrap; border-radius: 6px;">Copy Link</button>
                        </div>
                    </div>
                </article>

                <!-- Stats Summary Card -->
                <article class="referral-hero glass-surface" style="padding: 24px;">
                    <span class="referral-pill">TEAM STATS</span>
                    <h2 class="referral-title" style="font-size: 20px; margin: 8px 0; color: var(--gl-gold);">My Team Overview</h2>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 16px;">
                        <div class="referral-stat-card glass-surface" style="padding: 12px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;">
                            <i class="fa-solid fa-users-line" style="color: var(--gl-green); font-size: 18px;"></i>
                            <small style="font-size: 11px; color: var(--gl-muted); font-weight: 700;">Directs</small>
                            <strong style="font-size: 16px; color: var(--gl-text);">{{ $data->downline ?? 0 }}</strong>
                        </div>
                        <div class="referral-stat-card glass-surface" style="padding: 12px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;">
                            <i class="fa-solid fa-users" style="color: #06b6d4; font-size: 18px;"></i>
                            <small style="font-size: 11px; color: var(--gl-muted); font-weight: 700;">Team Size</small>
                            <strong style="font-size: 16px; color: var(--gl-text);">{{ $data->team ?? 0 }}</strong>
                        </div>
                        <div class="referral-stat-card glass-surface" style="padding: 12px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;">
                            <i class="fa-solid fa-chart-line" style="color: var(--gl-gold); font-size: 18px;"></i>
                            <small style="font-size: 11px; color: var(--gl-muted); font-weight: 700;">Team Biz</small>
                            <strong style="font-size: 14px; color: var(--gl-text); font-weight: 800;">${{ number_format($data->team_biz ?? 0, 0) }}</strong>
                        </div>
                    </div>
                </article>
            </div>

            <!-- VISUAL GENEALOGY BOARD -->
            <article class="referral-tree-card glass-surface" style="padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel); position: relative;">
                <!-- Board Toolbar -->
                <div class="genealogy-toolbar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--gl-border); padding-bottom: 16px;">
                    <div>
                        <h2 class="referral-title" style="font-size: 18px; margin: 0; color: var(--gl-gold);">Genealogy Structure</h2>
                    </div>
                    
                    <!-- Search Member Form -->
                    <form action="{{ route('member.referral.genealogy') }}" method="GET" style="display: flex; gap: 8px; align-items: center;">
                        <input type="text" name="memberid" value="{{ request('memberid') }}" placeholder="Enter Member ID..." required style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 600; outline: none; width: 180px; font-size: 13px;">
                        <button type="submit" class="pill-button" style="min-height: 34px; padding: 0 16px; font-size: 12.5px; font-weight: 700; border-radius: 8px;">Search</button>
                    </form>
 
                    <!-- Zoom Controls -->
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" id="zoomOutBtn" data-geneology-zoom-out class="pill-button" style="min-height: 32px; width: 32px; padding: 0; font-size: 13px; display: grid; place-items: center; border-radius: 8px;" title="Zoom Out"><i class="fa-solid fa-minus"></i></button>
                        <span id="zoomLabel" data-geneology-zoom-text style="font-size: 12.5px; font-weight: 700; color: var(--gl-muted); min-width: 44px; text-align: center;">100%</span>
                        <button type="button" id="zoomInBtn" data-geneology-zoom-in class="pill-button" style="min-height: 32px; width: 32px; padding: 0; font-size: 13px; display: grid; place-items: center; border-radius: 8px;" title="Zoom In"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" id="zoomResetBtn" data-geneology-zoom-reset class="pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;" title="Reset Zoom">Reset</button>
                    </div>
                </div>
 
                <!-- Up / Back to Root Navigation -->
                @if ($d_data->memberid !== $data->memberid)
                    <div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center;">
                        <a href="{{ route('member.referral.genealogy') }}" class="pill-button" style="min-height: 32px; padding: 0 16px; font-size: 12px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-house"></i> Back to My Root
                        </a>
                        @if (!empty($d_data->sponsorid))
                            @php
                                $sponsorDetail = \App\Models\MemberDetail::where('memberid', $d_data->sponsorid)->first();
                            @endphp
                            @if ($sponsorDetail)
                                <a href="{{ route('member.referral.view-genealogy', ['did' => $sponsorDetail->id]) }}" class="pill-button" style="min-height: 32px; padding: 0 16px; font-size: 12px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.05); color: var(--gl-text); border: 1px solid var(--gl-border);">
                                    <i class="fa-solid fa-chevron-up"></i> Go Up (Sponsor)
                                </a>
                            @endif
                        @endif
                    </div>
                @endif
 
                <!-- GENEALOGY TREE BOARD -->
                <div class="geneology-board">
                    <div class="geneology-tree-wrap" id="genealogyTree">
                        
                        <!-- Root/Focused Node -->
                        <div class="geneology-branch">
                            <div class="geneology-node-card root-node-card"
                                 data-memberid="{{ $d_data->memberid ?? 'N/A' }}"
                                 data-name="{{ $d_data->name ?? 'N/A' }}"
                                 data-email="{{ $d_data->email ?? 'N/A' }}"
                                 data-sponsorid="{{ $d_data->sponsorid ?? 'N/A' }}"
                                 data-rank="{{ $d_data->rank ?? $d_data->package ?? 'Basic' }}"
                                 data-status="{{ $d_data->status ?? 'Inactive' }}"
                                 style="border-color: var(--gl-gold); background: rgba(212, 175, 55, 0.05); box-shadow: 0 8px 30px rgba(212, 175, 55, 0.15);">
                                
                                <div class="geneology-node-avatar" style="color: var(--gl-gold); background: rgba(212, 175, 55, 0.1); border-color: var(--gl-gold);">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <div class="geneology-node-body">
                                    <strong style="color: var(--gl-gold);">Member ID: {{ $d_data->memberid ?? 'N/A' }}</strong>
                                    <span>Name: {{ $d_data->name ?? 'N/A' }}</span>
                                    <small>Sponsor: {{ $d_data->sponsorid ?? 'N/A' }}</small>
                                    <small>Rank: {{ $d_data->rank ?? $d_data->package ?? 'Basic' }}</small>
                                </div>
                            </div>
 
                            @if(!empty($tree))
                                <div class="geneology-children">
                                    @include('member.levels.partials.geneology-node', ['nodes' => $tree])
                                </div>
                            @else
                                <div class="geneology-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 12px; font-weight: 700; font-size: 12px; border: 1px dashed var(--gl-border); border-radius: 8px; background: rgba(255,255,255,0.02); min-width: 200px; margin-top: 24px;">
                                    <h3>No Downline Found</h3>
                                    <p>No members found under this Member ID.</p>
                                </div>
                            @endif
                        </div>
 
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
 
<div class="geneology-hover-card" id="geneologyHoverCard">
    <div class="geneology-hover-title">Member Details</div>
    <div><strong>Member ID:</strong> <span data-hover="memberid"></span></div>
    <div><strong>Name:</strong> <span data-hover="name"></span></div>
    <div><strong>Email:</strong> <span data-hover="email"></span></div>
    <div><strong>Sponsor ID:</strong> <span data-hover="sponsorid"></span></div>
    <div><strong>Rank/Package:</strong> <span data-hover="rank"></span></div>
    <div><strong>Status:</strong> <span data-hover="status"></span></div>
</div>
@endsection
