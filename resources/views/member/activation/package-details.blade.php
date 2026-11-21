@extends('member.layouts.app')

@section('title', 'Package Details - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Package Details')

@section('content')
<main class="member-page dashboard-page activation-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content activation-container">
            <!-- HERO BANNER -->
            <article class="page-hero-card glass-surface activation-hero">
                <div class="page-hero-content">
                    <div class="page-hero-left">
                        <span class="page-hero-pill activation-pill">ACTIVATION PLAN</span>
                        <h1 class="page-hero-title activation-title">Package Details</h1>
                        <p class="page-hero-desc activation-desc">View the activation registration amount and year-wise package distribution plan.</p>
                        <div class="page-hero-meta activation-meta">
                            <span class="page-hero-meta-pill">Registration: $70</span>
                            <span class="page-hero-meta-pill">Packages: 3</span>
                        </div>
                    </div>
                    <div class="page-hero-stats activation-stats">
                        <div class="page-hero-stat glass-surface activation-stat-card">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-dollar-sign" style="color: var(--gl-green);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Registration Amount</span>
                                <strong class="page-hero-stat-value">$70</strong>
                            </div>
                        </div>
                        <div class="page-hero-stat glass-surface activation-stat-card">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-calendar-days" style="color: var(--gl-gold);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Total Years</span>
                                <strong class="page-hero-stat-value">3</strong>
                            </div>
                        </div>
                        <div class="page-hero-stat glass-surface activation-stat-card">
                            <div class="page-hero-stat-icon">
                                <i class="fa-solid fa-diagram-project" style="color: var(--gl-muted);"></i>
                            </div>
                            <div class="page-hero-stat-text">
                                <span class="page-hero-stat-label">Plan Type</span>
                                <strong class="page-hero-stat-value" style="font-size: 14px;">Year Wise</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- THREE PACKAGE CARDS GRID -->
            <section class="activation-package-grid">
                @foreach ($packages as $pkg)
                    <article class="activation-package-card glass-surface">
                        <div class="activation-package-header">
                            <span class="activation-package-badge">Package {{ $pkg['code'] }}</span>
                        </div>
                        <h2 class="activation-package-year">{{ $pkg['year'] }}</h2>
                        <div class="activation-package-formula">
                            <span>Formula:</span>
                            <strong>${{ $pkg['registration_amount'] }} x {{ $pkg['multiplier'] }}</strong>
                        </div>
                        <div class="activation-package-total">
                            <span>Total Amount:</span>
                            <strong>${{ $pkg['total_amount'] }}</strong>
                        </div>
                        <p class="activation-card-desc">{{ $pkg['description'] }}</p>
                    </article>
                @endforeach
            </section>
        </div>
    </section>
</main>
@endsection
