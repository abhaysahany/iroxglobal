@extends('member.layouts.app')

@section('title', 'Main Wallet - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Main Wallet')

@section('content')
<main class="member-page dashboard-page wallet-section-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content wallet-section-container">
            <!-- HERO BANNER -->
            <article class="wallet-hero glass-surface" style="margin-bottom: 24px;">
                <div class="wallet-hero-content">
                    <div class="wallet-hero-left">
                        <span class="wallet-pill">WALLET OVERVIEW</span>
                        <h1 class="wallet-hero-title">Main Wallet</h1>
                        <p class="wallet-hero-desc">Manage your main wallet, withdraw funds, and track your earnings.</p>
                    </div>
                </div>
            </article>

            <!-- SESSION MESSAGES -->
            @if (session('successMsg'))
                <div class="wallet-alert wallet-alert-success">{{ session('successMsg') }}</div>
            @endif

            @if (session('failedMsg'))
                <div class="wallet-alert wallet-alert-error">{{ session('failedMsg') }}</div>
            @endif

            @if (session('error'))
                <div class="wallet-alert wallet-alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="wallet-alert wallet-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- INCOME WALLET STACK -->
            <div class="wallet-income-stack">
                <!-- BALANCE CARD -->
                <div class="wallet-income-balance-card glass-surface">
                    <div class="wallet-income-icon">
                        <i class="fa-solid fa-wallet"></i>
                    </div>

                    <div class="wallet-income-balance-content">
                        <span>Main Wallet Balance</span>
                        <strong>$ {{ number_format((float)($data->income_wallet ?? $data->main_wallet ?? $data->wallet ?? 0), 2) }}</strong>
                        <p>Available income balance for withdrawal.</p>
                    </div>
                </div>

                <!-- WITHDRAW CARD -->
                <div class="wallet-income-withdraw-card glass-surface">
                    <div class="wallet-withdraw-header">
                        <div class="wallet-withdraw-icon">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <div>
                            <h2>Withdraw Funds</h2>
                            <p>Request withdrawal from your main wallet securely.</p>
                        </div>
                    </div>

                    <form action="{{ route('member.wallet.income-withdraw') }}" method="POST" class="wallet-withdraw-form">
                        @csrf
                        <input type="hidden" name="memberid" value="{{ $data->memberid ?? '' }}">

                        <div class="wallet-form-group">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Enter Amount" step="0.01" min="1" required>
                        </div>

                        <div class="wallet-form-group">
                            <label for="txn_password">Transaction Password</label>
                            <input type="password" id="txn_password" name="txn_password" placeholder="Enter Transaction Password" required autocomplete="new-password">
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                            <button type="submit" class="pill-button" style="min-height: 44px; padding: 0 24px; font-weight: 700; border-radius: 12px; background: var(--gl-green); color: var(--gl-bg); border: none; cursor: pointer; transition: all 0.2s;display:inline-block">
                                Withdraw Funds
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
