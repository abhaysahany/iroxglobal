@extends('member.layouts.app')
@section('title', 'Global Rec Income - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Global Rec Income')

@section('content')
    <main class="member-page dashboard-page p2p-history-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')
            <div class="dashboard-content">

                <!-- GLASS TABLE CARD -->
                <article class="p2p-history-card glass-surface">
                    <div class="p2p-history-card-head"
                        style="padding: 20px 24px; border-bottom: 1px solid var(--gl-border); display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="p2p-history-title"
                            style="font-size: 18px; font-weight: 800; margin: 0; color: var(--gl-gold);">Global Rec Income
                        </h2>
                    </div>

                    <!-- TABLE TOOLBAR -->
                    <div class="p2p-history-toolbar"
                        style="padding: 16px 24px; border-bottom: 1px solid var(--gl-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div class="p2p-history-show">
                            <label style="font-size: 13px; font-weight: 700; color: var(--gl-muted);">
                                Show
                                <select name="entries" class="p2p-history-show-select" aria-label="Select entries number"
                                    style="background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text); padding: 4px 8px; border-radius: 6px; outline: none;">
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
                                <input type="search" class="p2p-history-search-input" placeholder="Search history..."
                                    aria-label="Search history"
                                    style="background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text); padding: 6px 12px; border-radius: 8px; outline: none; margin-left: 8px;">
                            </label>
                        </div>
                    </div>

                    <!-- TABLE WRAPPER FOR HORIZONTAL SCROLL -->
                    <div class="p2p-history-table-wrap">
                        <table class="p2p-history-table">
                            <thead>
                                <tr>
                                    <th class="income-sort sort-asc" data-column="0">S.No. <i
                                            class="fa-solid fa-sort-up sort-icon"></i></th>
                                    <th class="income-sort" data-column="1">Date <i class="fa-solid fa-sort sort-icon"></i>
                                    </th>
                                    <th class="income-sort" data-column="2">Memberid <i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                    <th class="income-sort" data-column="3">Pool No <i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                    <th class="income-sort" data-column="4">ActivatingId <i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                    <th class="income-sort" data-column="6">Sponsor Name <i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                    <th class="income-sort" data-column="5">Total Income<i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                    <th class="income-sort" data-column="7">Rate <i class="fa-solid fa-sort sort-icon"></i>
                                    </th>
                                    <th class="income-sort" data-column="8">Amount <i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                    <th class="income-sort" data-column="9">Status <i
                                            class="fa-solid fa-sort sort-icon"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($data as $list)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                       <td style="padding: 12px 16px;">
                                          <div style="font-weight: 700;">
                                               {{ $list['memberid'] }}
                                           </div>

                                          <div style="font-size: 12px; margin-top: 3px; opacity: 0.8;">
                                               {{ getname($list['memberid']) }}
                                             </div>
                                        </td>
                                        <td>{{ $list->pool_no }}</td>
                                        <td>{{ $list->activatingId }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>$ {{ number_format((float) $list->total_income, 2) }}</td>
                                        <td>{{ $list->rate }}%</td>
                                        <td>$ {{ number_format((float) $list->amount, 2) }}</td>
                                        <td>
                                            @if ($list['status'] == 'Paid')
                                                <div class="fund-history-status approved">{{ $list['status'] }}</label>
                                                @else
                                                    <div class="fund-history-status rejected">{{ $list['status'] }}</label>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION WRAPPER -->
                    <div class="income-pagination-info"
                        style="padding: 16px 24px; border-top: 1px solid var(--gl-border); font-size: 13px; color: var(--gl-muted); font-weight: 600; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <span id="js-p2p-pagination-summary">Showing 0 to 0 of 0 entries</span>
                        <div class="income-pagination-buttons" style="display: flex; gap: 8px;">
                            <button type="button" class="pill-button" id="js-p2p-prev-btn"
                                style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px; cursor: pointer; background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text);">Previous</button>
                            <button type="button" class="pill-button" id="js-p2p-next-btn"
                                style="min-height: 32px; padding: 0 12px; font-size: 12px; font-weight: 700; border-radius: 8px; cursor: pointer; background: var(--gl-input); border: 1px solid var(--gl-border); color: var(--gl-text);">Next</button>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </main>
@endsection
