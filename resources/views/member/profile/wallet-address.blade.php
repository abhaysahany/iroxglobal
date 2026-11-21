@extends('member.layouts.app')

@section('title', 'Wallet Address - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Wallet Address')

@section('content')
    @php
        $walletAddressLocked = !empty($data->wallet_address);
    @endphp
    <style>
        .profile-wallet-page .profile-wallet-layout {
            display: flex !important;
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            grid-template-columns: none !important;
        }

        .profile-wallet-page .profile-wallet-column-left {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            max-width: none;
        }

        .profile-wallet-page .wallet-verification-strip {
            width: 100%;
            margin-bottom: 24px !important;
        }

        .profile-wallet-page .wallet-address-update-card {
            width: 100%;
            max-width: 760px;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-wallet-page .profile-wallet-submit-wrap {
            display: flex;
            justify-content: end;
        }

        .profile-wallet-page .profile-wallet-status-badge.pending {
            color: var(--gl-danger);
            border-color: color-mix(in srgb, var(--gl-danger) 45%, transparent);
            background: color-mix(in srgb, var(--gl-danger) 12%, transparent);
        }

        .profile-wallet-page .profile-wallet-input:disabled {
            cursor: not-allowed;
            opacity: 0.75;
        }

        .profile-wallet-page .profile-wallet-btn:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            box-shadow: none;
        }

        .profile-wallet-page .wallet-address-lock-note {
            margin: 10px 0 0;
            color: var(--gl-muted);
            font-size: 13px;
            font-weight: 600;
        }
    </style>
    <main class="member-page dashboard-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content profile-wallet-page">
                @if (session('successMsg'))
                    <div class="glass-alert success dashboard-alert">{{ session('successMsg') }}</div>
                @endif

                @if (session('failedMsg'))
                    <div class="glass-alert danger dashboard-alert">{{ session('failedMsg') }}</div>
                @endif



                <!-- TWO-COLUMN LAYOUT -->
                <div class="profile-wallet-layout">
                    <div class="profile-wallet-column-left">
                        <article class="profile-wallet-card glass-surface wallet-verification-strip" style="margin-bottom: 24px;">
                            <div class="profile-wallet-card-header">
                                <h2>Wallet Verification</h2>
                            </div>
                            <div class="profile-wallet-status-list">
                                <div class="profile-wallet-status-item">
                                    <span>Wallet Status</span>
                                    <span
                                        class="profile-wallet-status-badge {{ $walletAddressLocked ? 'success' : 'pending' }}">{{ $walletAddressLocked ? 'Submitted' : 'Pending' }}</span>
                                </div>
                            </div>

                        </article>

                        <!-- UPDATE WALLET ADDRESS CARD -->
                        <article class="profile-wallet-card glass-surface wallet-address-update-card">
                            <div class="profile-wallet-card-header">
                                <h2>Update Wallet Address</h2>
                            </div>

                            <form action="{{ route('member.profile.wallet-address.update') }}" method="POST"
                                class="js-form-loading profile-wallet-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <div class="profile-wallet-form-grid" style="display: grid; gap: 16px;">
                                    <div class="profile-wallet-field full">
                                        <label for="wallet_address">Wallet Address</label>
                                        <input id="wallet_address" type="text" name="wallet_address"
                                            class="profile-wallet-input"
                                            value="{{ old('wallet_address', $data->wallet_address ?? '') }}"
                                            placeholder="Enter BSC BNB Smart Chain (BEP20) Wallet Address"
                                            @disabled($walletAddressLocked)>
                                        @if ($walletAddressLocked)
                                            <p class="wallet-address-lock-note">Wallet address can be updated only one time.</p>
                                        @endif
                                        @error('wallet_address')
                                            <small class="profile-wallet-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                </div>

                                <div class="profile-wallet-submit-wrap" style="margin-top: 20px;">
                                    <button type="submit" class="profile-wallet-btn" @disabled($walletAddressLocked)>
                                        <span>Update Wallet Address</span>
                                        <i class="fa-solid fa-floppy-disk"></i>
                                    </button>
                                </div>
                            </form>
                        </article>
                    </div>

               
                </div>
            </div>
        </section>
    </main>
@endsection
