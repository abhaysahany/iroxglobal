@extends('member.layouts.app')

@section('title', 'Level Members - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Level Members')

@section('content')
<style>
    .referral-page .level-members-table {
        table-layout: fixed;
    }

    .referral-page .level-members-table th:nth-child(1),
    .referral-page .level-members-table td:nth-child(1) {
        width: 16%;
    }

    .referral-page .level-members-table th:nth-child(2),
    .referral-page .level-members-table td:nth-child(2) {
        width: 24%;
    }

    .referral-page .level-members-table th:nth-child(3),
    .referral-page .level-members-table td:nth-child(3) {
        width: 24%;
    }

    .referral-page .level-members-table th:nth-child(4),
    .referral-page .level-members-table td:nth-child(4) {
        width: 140px;
        text-align: center !important;
    }

    .referral-page .level-members-view-btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 112px !important;
        min-width: 112px !important;
        max-width: 112px !important;
        min-height: 30px !important;
        padding: 0 10px !important;
        border: 1px solid var(--gl-green) !important;
        border-radius: 8px !important;
        background: linear-gradient(135deg, var(--gl-green), var(--gl-green-dark)) !important;
        color: #fff !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        white-space: nowrap;
        box-shadow: none !important;
    }

    .referral-page .level-members-view-btn:hover {
        background: linear-gradient(135deg, var(--gl-green-dark), var(--gl-green)) !important;
        border-color: var(--gl-green-dark) !important;
        color: #fff !important;
    }
</style>
<main class="member-page dashboard-page referral-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
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
                                    <small>Structure</small>
                                    <strong>Multi-Level</strong>
                                </div>
                            </div>
                            <div class="referral-stat-card glass-surface">
                                <i class="fa-solid fa-users" style="color: var(--gl-green);"></i>
                                <div>
                                    <small>Directs</small>
                                    <strong>{{ $data->downline ?? 0 }}</strong>
                                </div>
                            </div>
                            <div class="referral-stat-card glass-surface">
                                <i class="fa-solid fa-eye" style="color: #06b6d4;"></i>
                                <div>
                                    <small>Action</small>
                                    <strong>Interactive</strong>
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

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="referral-table-wrap" style="width: 100%; overflow-x: auto; border: 1px solid var(--gl-border); border-radius: 14px; background: var(--gl-panel-soft); box-shadow: inset 0 2px 8px var(--gl-shadow);">
                    <table class="referral-table level-members-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--gl-border-strong); background: rgba(5, 15, 8, 0.4);">
                                <th style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">S.No.</th>
                                <th style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">Level No</th>
                                <th style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">Total Members</th>
                                <th style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 20; $i++)
                                @php
                                    $count = 0;
                                    try {
                                        $count = \App\Models\UplineMember::where('upline_' . $i, $data->memberid)->count();
                                    } catch (\Exception $e) {
                                        $count = 0;
                                    }
                                @endphp
                                <tr style="border-bottom: 1px solid var(--gl-border);">
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $i }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap; color: var(--gl-green);">Level {{ $i }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ number_format($count) }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap; text-align: right;">
                                        <a href="{{ route('member.referral.level-members.details', ['level' => $i]) }}" class="btn btn-primary level-members-view-btn">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
