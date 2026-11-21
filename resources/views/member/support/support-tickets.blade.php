@extends('member.layouts.app')

@section('title', 'Support Tickets - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Support Tickets')

@section('content')
<main class="member-page dashboard-page support-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            @php
                $ticketsCollection = collect($support ?? []);
                $latestTicket = $ticketsCollection->first();
                $latestTicketId = $latestTicket ? ($latestTicket->ticket_id ?? $latestTicket->id ?? '-') : '-';
                $latestSubject = $latestTicket ? ($latestTicket->subject ?? '-') : '-';
                $latestStatus = $latestTicket ? ($latestTicket->status ?? 'Open') : 'No Tickets';
                $totalTickets = $ticketsCollection->count();
            @endphp

            <!-- BREADCRUMB -->
            <!--<div class="support-breadcrumb" style="margin-bottom: 20px; font-size: 13px; font-weight: 700; color: var(--gl-muted);">-->
            <!--    Demo Wealth Portal / Support Tickets-->
            <!--</div>-->

            <!-- HERO SUMMARY CARD -->
            <article class="support-hero glass-surface" style="border-radius: 16px; border: 1px solid var(--gl-border); background: linear-gradient(135deg, rgba(5, 15, 8, 0.65), rgba(73, 133, 36, 0.1)); box-shadow: var(--gl-shadow); backdrop-filter: blur(10px); padding: 30px;">
                <div class="support-hero-content" style="display: flex; justify-content: space-between; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div class="support-hero-left" style="flex: 1 1 450px;">
                        <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">CUSTOMER SUPPORT</span>
                        <h1 class="support-title" style="font-size: 32px; font-weight: 800; color: var(--gl-gold); margin: 0 0 12px;">Support Tickets</h1>
                        <p class="support-desc" style="font-size: 14px; line-height: 1.5; color: var(--gl-text); margin: 0 0 20px;">View and manage your support tickets. Track ticket status, view details, and create a new ticket for quick resolution.</p>
                        <div class="support-meta" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Tickets: {{ $totalTickets }}</span>
                            <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">24/7 Support</span>
                        </div>
                    </div>
                    <div class="support-hero-right" style="flex: 1 1 320px;">
                        <div class="support-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">
                            <div class="support-stat-card glass-surface" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft);">
                                <i class="fa-solid fa-ticket" style="color: var(--gl-gold); font-size: 18px;"></i>
                                <div style="display: flex; flex-direction: column;">
                                    <small style="font-size: 10px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Latest Ticket</small>
                                    <strong style="font-size: 14px; font-weight: 800; color: var(--gl-text);">{{ $latestTicketId }}</strong>
                                </div>
                            </div>
                            <div class="support-stat-card glass-surface" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft);">
                                <i class="fa-solid fa-circle-question" style="color: var(--gl-green); font-size: 18px;"></i>
                                <div style="display: flex; flex-direction: column;">
                                    <small style="font-size: 10px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Subject</small>
                                    <strong style="font-size: 14px; font-weight: 800; color: var(--gl-text); text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width: 120px;" title="{{ $latestSubject }}">{{ $latestSubject }}</strong>
                                </div>
                            </div>
                            <div class="support-stat-card glass-surface" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft); grid-column: span 2;">
                                <i class="fa-solid fa-spinner" style="color: #06b6d4; font-size: 18px;"></i>
                                <div style="display: flex; flex-direction: column;">
                                    <small style="font-size: 10px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Status</small>
                                    <strong style="font-size: 14px; font-weight: 800; color: var(--gl-text);">{{ $latestStatus }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- DETAILS TABLE -->
            <article class="support-table-card glass-surface" style="margin-top: 24px; padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel);">
                <div class="support-table-card-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--gl-border); padding-bottom: 16px; flex-wrap: wrap; gap: 16px;">
                    <h2 class="support-table-title" style="font-size: 18px; margin: 0; color: var(--gl-gold); font-weight: 800;">Your Support Tickets</h2>
                    <a href="{{ route('member.support.create-ticket') }}" class="support-create-btn" style="display: inline-flex; align-items: center; justify-content: center; min-height: 38px; padding: 0 20px; font-size: 12px; font-weight: 800; border-radius: 8px; text-decoration: none; background: linear-gradient(135deg, var(--gl-green), #06b6d4); color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2); transition: transform 0.2s, box-shadow 0.2s;">
                        <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Create New Ticket
                    </a>
                </div>

                <!-- TABLE TOOLBAR -->
                <div class="support-table-toolbar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                    <div class="support-table-show">
                        <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                            Show 
                            <select name="entries" class="support-show-select" aria-label="Select entries count" style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 700; outline: none; cursor: pointer;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> 
                            entries
                        </label>
                    </div>
                    <div class="support-table-search">
                        <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                            Search:
                            <input type="search" class="support-search-input" placeholder="Search tickets..." aria-label="Search entries" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(5,15,8,0.4); color: var(--gl-text); font-weight: 600; outline: none; margin-left: 8px; width: 200px; transition: border-color 0.2s;">
                        </label>
                    </div>
                </div>

                <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                <div class="support-table-wrap" style="width: 100%; overflow-x: auto; border: 1px solid var(--gl-border); border-radius: 14px; background: var(--gl-panel-soft); box-shadow: inset 0 2px 8px var(--gl-shadow);">
                    <table class="support-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--gl-border-strong); background: rgba(5, 15, 8, 0.4);">
                                <th class="support-sort sort-asc" data-column="0" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">S.No. <i class="fa-solid fa-sort-up sort-icon" style="font-size: 11px; margin-left: 6px;"></i></th>
                                <th class="support-sort" data-column="1" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Ticket ID <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="support-sort" data-column="2" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Subject <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="support-sort" data-column="3" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Created Date <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th class="support-sort" data-column="4" style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none;">Status <i class="fa-solid fa-sort sort-icon" style="font-size: 11px; margin-left: 6px; color: var(--gl-muted);"></i></th>
                                <th style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px; user-select: none;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($support ?? [] as $index => $row)
                                <tr style="border-bottom: 1px solid var(--gl-border);">
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $index + 1 }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row->ticket_id ?? $row->id ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row->subject ?? '' }}</td>
                                    <td data-sort-val="{{ Carbon\Carbon::parse($row->created_at ?? '')->timestamp ?? 0 }}" style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">{{ $row->created_at ?? '' }}</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                        @php
                                            $statusStr = $row->status ?? 'Open';
                                            $status = strtolower(str_replace(' ', '-', $statusStr));
                                        @endphp
                                        <span class="support-status support-status-{{ $status }}">
                                            {{ $statusStr }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--gl-text); white-space: nowrap;">
                                        <a href="{{ route('member.support.view-support-tickets', ['tid' => $row->ticket_id ?? $row->id]) }}" class="support-action-btn">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="js-empty-row">
                                    <td colspan="6" class="support-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">No data available in table</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION WRAPPER -->
                <div class="support-table-pagination" style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 13px; color: var(--gl-muted); font-weight: 600;">
                    <span class="support-table-pagination-summary">Showing 0 to 0 of 0 entries</span>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="support-table-btn support-table-prev-btn pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Previous</button>
                        <button type="button" class="support-table-btn support-table-next-btn pill-button" style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px;">Next</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
