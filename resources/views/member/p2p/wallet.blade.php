@extends('member.layouts.app')

@section('title', 'P2P Wallet - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'P2P Wallet')

@section('content')
<main class="member-page dashboard-page p2p-section-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content p2p-section-container">
            <div class="p2p-layout-grid">
                <!-- LEFT INFO COLUMN -->
                <div class="p2p-info-column">
                    <div class="p2p-info-card glass-surface">
                        <div class="p2p-info-icon">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <span>Available Balance</span>
                            <h3>$ {{ number_format((float)($data->p2p_wallet ?? 0), 2) }}</h3>
                            <p>Your current P2P wallet balance for member transfers.</p>
                        </div>
                    </div>

                    <div class="p2p-info-card glass-surface">
                        <div class="p2p-info-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <span>Secure Transfer</span>
                            <h3>PIN Protected</h3>
                            <p>Every transfer requires your Security PIN before submission.</p>
                        </div>
                    </div>

                    <div class="p2p-info-card glass-surface">
                        <div class="p2p-info-icon">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <span>Records</span>
                            <h3>Transfer History</h3>
                            <p>Track your P2P wallet transfer activity and status.</p>
                            <a href="{{ route('member.p2p.wallet-history') }}" class="p2p-info-link">View History</a>
                        </div>
                    </div>
                </div>

                <!-- RIGHT FORM COLUMN -->
                <div class="p2p-form-column">
                    <!-- Glassmorphism Transfer Card -->
                    <article class="p2p-transfer-card glass-surface">
                        <div class="p2p-card-title">
                            <h2>P2P Wallet Transfer Form</h2>
                            <p>Transfer funds securely to another member’s P2P wallet.</p>
                        </div>
                        
                        <div class="p2p-card-body">
                            <!-- SUCCESS / ERROR ALERTS -->
                            @if (session('successMsg'))
                                <div class="p2p-message p2p-message-success">
                                    <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                                    {{ session('successMsg') }}
                                </div>
                            @endif

                            @if (session('failedMsg'))
                                <div class="p2p-message p2p-message-error">
                                    <i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i>
                                    {{ session('failedMsg') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="p2p-message p2p-message-error">
                                    <i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="p2p-message p2p-message-error">
                                    <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- TRANSFER FORM -->
                            <form action="{{ route('member.p2p.wallet.submit') }}" method="POST" class="p2p-transfer-form">
                                @csrf

                                <div class="p2p-form-group">
                                    <label for="memberid">Member ID</label>
                                    <input id="memberid" type="text" name="memberid" value="{{ old('memberid') }}" placeholder="Enter Member ID" required>
                                    <span id="memMsg"></span>
                                    @error('memberid')
                                        <small class="p2p-wallet-error" style="color: #ff9a9a; font-weight: 700; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="p2p-form-group">
                                    <label for="amount">Amount ($)</label>
                                    <input id="amount" type="number" name="amount" value="{{ old('amount') }}" placeholder="Enter Amount" step="0.01" min="1" required>
                                    @error('amount')
                                        <small class="p2p-wallet-error" style="color: #ff9a9a; font-weight: 700; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="p2p-form-group">
                                    <label for="txn_password">Security Pin</label>
                                    <input id="txn_password" type="password" name="txn_password" placeholder="Enter Security Pin" required autocomplete="new-password">
                                    @error('txn_password')
                                        <small class="p2p-wallet-error" style="color: #ff9a9a; font-weight: 700; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="green-button p2p-submit-btn">Transfer Now</button>
                            </form>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
