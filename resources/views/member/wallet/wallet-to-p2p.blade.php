@extends('member.layouts.app')

@section('title', 'Wallet To P2P - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Wallet To P2P')

@section('content')
<main class="member-page dashboard-page p2p-wallet-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content p2p-wallet-container">
            <!-- HERO BANNER -->
            <article class="wallet-hero glass-surface" style="margin-bottom: 24px;">
                <div class="wallet-hero-content">
                    <div class="wallet-hero-left">
                        <span class="wallet-pill">WALLET TRANSFER</span>
                        <h1 class="wallet-hero-title">Wallet To P2P</h1>
                        <p class="wallet-hero-desc">Transfer funds from your main wallet to P2P wallet. Enter the amount and transaction password to complete the transfer.</p>
                        <div class="wallet-hero-tags">
                            <span class="wallet-pill">Main Wallet: ${{ number_format(($data->wallet ?? 0), 2) }}</span>
                            <span class="wallet-pill">P2P Wallet: ${{ number_format(($data->p2p_wallet ?? 0), 2) }}</span>
                        </div>
                    </div>
                    <div class="wallet-hero-right">
                        <div class="wallet-hero-stats">
                            <div class="wallet-stat-mini glass-surface">
                                <i class="fa-solid fa-wallet" style="color: var(--gl-gold);"></i>
                                <div>
                                    <small>Main Wallet Balance</small>
                                    <strong class="wallet-balance-amount">$ {{ number_format(($data->wallet ?? 0), 2) }}</strong>
                                </div>
                            </div>
                            <div class="wallet-stat-mini glass-surface">
                                <i class="fa-solid fa-arrow-right-arrow-left" style="color: #06b6d4;"></i>
                                <div>
                                    <small>P2P Wallet Balance</small>
                                    <strong class="wallet-balance-amount" style="color: #06b6d4;">$ {{ number_format(($data->p2p_wallet ?? 0), 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- TRANSFER FORM CARD -->
            <article class="wallet-transfer-card glass-surface" style="max-width: 800px; margin: 0 auto; padding: 36px;">
                <div style="text-align: center; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px solid var(--gl-border);">
                    <div class="wallet-icon-wrap" style="width: 54px; height: 54px; background: rgba(73,133,36,0.1); border-radius: 50%; display: grid; place-items: center; margin: 0 auto 16px;">
                        <i class="fa-solid fa-money-bill-transfer" style="font-size: 24px; color: var(--gl-green);"></i>
                    </div>
                    <h2 class="wallet-label" style="font-size: 20px; font-weight: 800; margin: 0 0 6px; color: var(--gl-gold);">Transfer To P2P Wallet</h2>
                    <!--<p style="font-size: 13px; font-weight: 700; color: var(--gl-muted); margin: 0 0 12px;">Demo Wealth Portal Main Wallet Balance</p>-->
                    <h3 class="wallet-balance-amount" style="font-size: 36px; font-weight: 800; margin: 0;">$ {{ number_format(($data->wallet ?? 0), 2) }}</h3>
                </div>

                <!-- SUCCESS / ERROR SESSION ALERTS -->
                @if (session('successMsg'))
                    <div class="glass-alert success dashboard-alert wallet-alert" style="background: rgba(130, 226, 63, 0.12); border: 1px solid rgba(130, 226, 63, 0.28); color: var(--gl-green); padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                        {{ session('successMsg') }}
                    </div>
                @endif

                @if (session('failedMsg'))
                    <div class="glass-alert danger dashboard-alert wallet-alert" style="background: rgba(220,53,69,0.15); border: 1px solid var(--gl-danger); color: #f87171; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i>
                        {{ session('failedMsg') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="glass-alert danger dashboard-alert wallet-alert" style="background: rgba(220,53,69,0.15); border: 1px solid var(--gl-danger); color: #f87171; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                        <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- TRANSFER FORM -->
                <form action="{{ route('member.wallet.to-p2p.submit') }}" method="POST" class="wallet-transfer-form">
                    @csrf
                    
                    <input type="hidden" name="sender" value="{{ $data->memberid }}">

                    <div class="wallet-form-field" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <label for="wallet_balance" class="wallet-label">Main Wallet Balance</label>
                        <div class="p2p-wallet-input-wrapper">
                            <i class="fa-solid fa-wallet"></i>
                            <input id="wallet_balance" name="wallet_balance" type="text" value="{{ number_format(($data->wallet ?? 0), 2, '.', '') }}" class="p2p-wallet-input" readonly>
                        </div>
                    </div>

                    <div class="wallet-form-field" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <label for="amount" class="wallet-label">Amount</label>
                        <div class="p2p-wallet-input-wrapper">
                            <i class="fa-solid fa-dollar-sign"></i>
                            <input id="amount" name="amount" type="number" step="0.01" min="1" placeholder="Enter Amount" class="p2p-wallet-input" value="{{ old('amount') }}" required>
                        </div>
                        @error('amount')
                            <small class="wallet-error" style="color: #f87171; font-weight: 600; font-size: 11.5px; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="wallet-form-field" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <label for="txn_password" class="wallet-label">Transaction Password</label>
                        <div class="p2p-wallet-input-wrapper">
                            <i class="fa-solid fa-key"></i>
                            <input id="txn_password" name="txn_password" type="password" placeholder="Enter Transaction Password" class="p2p-wallet-input" required autocomplete="new-password">
                        </div>
                        @error('txn_password')
                            <small class="wallet-error" style="color: #f87171; font-weight: 600; font-size: 11.5px; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="wallet-actions" style="display: flex; justify-content: flex-end; margin-top: 24px;">
                        <button type="submit" class="wallet-btn">
                            <span>Transfer Amount</span>
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </article>
        </div>
    </section>
</main>
@endsection
