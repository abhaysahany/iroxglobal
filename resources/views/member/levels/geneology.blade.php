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
            <div class="geneology-premium-page">
                <div class="geneology-topbar glass-surface">
                    <div class="geneology-title-block">
                        <span class="geneology-leaf-icon">
                            <i class="fa-solid fa-seedling"></i>
                        </span>
                        <div>
                            <h2>Genealogy</h2>
                            <p>Your network. Your growth. Our GreenLife.</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('member.referral.genealogy') }}" class="geneology-search-form">
                        <input type="text" name="memberid" value="{{ request('memberid') }}" placeholder="Search member by ID or name...">
                        <button type="submit" aria-label="Search member">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <div class="geneology-zoom-controls">
                        <button type="button" data-geneology-fullscreen aria-label="Fullscreen genealogy board">
                            <i class="fa-solid fa-expand"></i>
                        </button>
                        <button type="button" data-geneology-zoom-out aria-label="Zoom out">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <span data-geneology-zoom-text>100%</span>
                        <button type="button" data-geneology-zoom-in aria-label="Zoom in">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <button type="button" data-geneology-zoom-reset>Reset</button>
                    </div>
                </div>

                <div class="geneology-board glass-surface" id="geneologyBoard">
                    <div class="geneology-tree-canvas" id="geneologyTreeCanvas">
                        @if(!empty($data))
                            <div class="geneology-root-wrap">
                                <a href="{{ route('member.referral.genealogy', ['memberid' => $data->memberid ?? '']) }}"
                                   class="geneology-root-card geneology-node-card geneology-hover-target"
                                   data-memberid="{{ $data->memberid ?? 'N/A' }}"
                                   data-name="{{ $data->name ?? 'N/A' }}"
                                   data-email="{{ $data->email ?? 'N/A' }}"
                                   data-sponsorid="{{ $data->sponsorid ?? 'Root' }}"
                                   data-rank="{{ $data->rank ?? $data->package ?? 'Basic' }}"
                                   data-status="{{ $data->status ?? 'Inactive' }}">
                                    <div class="geneology-root-icon">
                                        <i class="fa-solid fa-crown"></i>
                                    </div>
                                    <h3>Member ID: {{ $data->memberid ?? 'N/A' }}</h3>
                                    <p>Name: {{ $data->name ?? 'N/A' }}</p>
                                    <span>Sponsor: {{ $data->sponsorid ?? 'Root' }}</span>
                                    <span>Rank: {{ $data->rank ?? $data->package ?? 'Basic' }}</span>
                                    <b>{{ $data->status ?? 'Active' }}</b>
                                </a>
                            </div>

                            @if(!empty($tree))
                                <div class="geneology-branches-root">
                                    @include('member.levels.partials.geneology-node', ['nodes' => $tree, 'level' => 1])
                                </div>
                            @else
                                <div class="geneology-empty">
                                    <h3>No Downline Found</h3>
                                    <p>No members found under this Member ID.</p>
                                </div>
                            @endif
                        @else
                            <div class="geneology-empty">
                                <h3>Member Not Found</h3>
                                <p>Please search a valid Member ID.</p>
                            </div>
                        @endif
                    </div>

                    <div class="geneology-hover-card" id="geneologyHoverCard">
                        <h4>Member Details</h4>
                        <p><strong>Member ID:</strong> <span data-hover="memberid"></span></p>
                        <p><strong>Name:</strong> <span data-hover="name"></span></p>
                        <p><strong>Email:</strong> <span data-hover="email"></span></p>
                        <p><strong>Sponsor ID:</strong> <span data-hover="sponsorid"></span></p>
                        <p><strong>Rank/Package:</strong> <span data-hover="rank"></span></p>
                        <p><strong>Status:</strong> <span data-hover="status"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
