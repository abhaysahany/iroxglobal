@extends('member.layouts.app')

@section('title', 'Account Created - ' . config('detailsApp.name'))
@section('body_class', 'member-auth-body')

@section('content')
<main class="member-page auth-page account-created-page">
    <button class="theme-toggle floating-theme-toggle js-theme-toggle" type="button" aria-label="Toggle theme">
        <i class="fa-solid fa-sun"></i>
    </button>

    <section class="auth-visual">
        <a class="auth-logo" href="{{ route('member.login') }}">
            <img src="{{ asset('member/assets/images/logo-greenlife.png') }}" alt="logo">
        </a>

        <div class="auth-copy">
            <h1>Account <span>Created!</span></h1>
            <p>Welcome to {{ config('detailsApp.name') }}. You are ready to explore the global community.</p>
        </div>

        <div class="feature-list compact-features">
            <div><i class="fa-solid fa-shield-halved"></i><span>Secure Platform</span></div>
            <div><i class="fa-solid fa-earth-americas"></i><span>Global Community</span></div>
            <div><i class="fa-solid fa-seedling"></i><span>Endless Growth</span></div>
            <div><i class="fa-solid fa-award"></i><span>Trusted & Verified</span></div>
        </div>

        @include('member.layouts.footer')
    </section>

    <section class="auth-panel-wrap">
        <div class="auth-card account-created-card glass-surface" style="text-align: center; display: flex; flex-direction: column; align-items: center; gap: 20px; padding: 40px 30px;">
            <div class="account-created-icon" style="width: 80px; height: 80px; border-radius: 50%; background: color-mix(in srgb, var(--gl-green) 15%, transparent); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; border: 1px solid var(--gl-green);">
                <i class="fa-solid fa-circle-check" style="font-size: 40px; color: var(--gl-green);"></i>
            </div>
            
            <h2 class="account-created-title" style="font-size: 24px; font-weight: 700; color: var(--gl-gold); margin: 0; font-family: 'Space Grotesk', sans-serif;">Account Created Successfully</h2>
            <p style="font-size: 14px; color: var(--gl-text); line-height: 1.5; margin: 0; max-width: 320px;">
                Your account has been created successfully. Please login to continue.
            </p>
            <div style="font-size: 13px; color: var(--gl-muted); font-weight: 600; margin-top: -5px;">
                Welcome to {{ config('detailsApp.name') }}
            </div>

            <style>
                .account-created-details {
                    width: 100%;
                    background: rgba(255, 255, 255, 0.03);
                    border: 1px solid var(--gl-border);
                    border-radius: 12px;
                    padding: 16px;
                    text-align: left;
                    margin: 10px 0;
                    box-sizing: border-box;
                }
                .account-created-details h3 {
                    margin: 0 0 12px 0;
                    font-size: 15px;
                    font-weight: 700;
                    color: var(--gl-gold);
                    font-family: 'Space Grotesk', sans-serif;
                    border-bottom: 1px solid var(--gl-border);
                    padding-bottom: 6px;
                }
                .account-created-detail-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 6px 0;
                    font-size: 13px;
                    line-height: 1.4;
                    border-bottom: 1px dotted rgba(255, 255, 255, 0.08);
                }
                .account-created-detail-row:last-child {
                    border-bottom: none;
                    padding-bottom: 0;
                }
                .account-created-detail-label {
                    color: var(--gl-muted);
                    font-weight: 600;
                    padding-right: 10px;
                }
                .account-created-detail-value {
                    color: var(--gl-text);
                    font-weight: 700;
                    text-align: right;
                    word-break: break-all;
                }
                @media (max-width: 576px) {
                    .account-created-detail-row {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 2px;
                        padding: 8px 0;
                    }
                    .account-created-detail-value {
                        text-align: left;
                    }
                }
            </style>

            <div class="account-created-details">
                <h3>Your Account Details</h3>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Member ID:</span>
                    <span class="account-created-detail-value">{{ $member->memberid ?? session('userid') ?? '-' }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Name:</span>
                    <span class="account-created-detail-value">{{ $member->name ?? session('name') ?? '-' }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Email:</span>
                    <span class="account-created-detail-value">{{ $member->email ?? session('email') ?? '-' }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Password:</span>
                    <span class="account-created-detail-value">{{ session('password') }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Transaction Password:</span>
                    <span class="account-created-detail-value">{{ $member->txnpass ?? session('txn_password') ?? '-' }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Mobile:</span>
                    <span class="account-created-detail-value">{{ $member->mobile ?? session('mobile') ?? '-' }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Country:</span>
                    <span class="account-created-detail-value">{{ $member->country ?? session('country') ?? '-' }}</span>
                </div>
                {{-- <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Sponsor ID:</span>
                    <span class="account-created-detail-value">{{ $member->sponsorid ?? '-' }}</span>
                </div>
                <div class="account-created-detail-row">
                    <span class="account-created-detail-label">Sponsor Name:</span>
                    <span class="account-created-detail-value">{{ $member->sponsor_name ?? '-' }}</span>
                </div> --}}
            </div>
            
            <a href="{{ route('member.login') }}" class="account-created-btn green-button" style="width: 100%; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 10px;">
                <span>Login Now</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>
</main>
@endsection
