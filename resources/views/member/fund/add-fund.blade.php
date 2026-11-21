@extends('member.layouts.app')

@section('title', 'Add Fund - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Add Fund')

@section('content')
    <main class="member-page dashboard-page fund-add-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content fund-add-container">
                @if (session('success'))
                    <div class="glass-alert success dashboard-alert">{{ session('success') }}</div>
                @endif

                <div class="fund-add-layout">
                    <!-- LEFT COLUMN: PREVIEW & FORM -->
                    <div class="fund-add-column-left">
                        <!-- SECTION 2: ADD FUND FORM CARD -->
                        <article class="fund-add-card glass-surface" style="margin-top: 40px;">
                            <div class="fund-add-card-header">
                                <h2>Add Fund </h2>
                                <p>Enter the amount of funds you want to add.</p>
                            </div>

                            {{-- <form action="{{ route('member.add-fund.submit') }}" method="POST" class="fund-add-form">
                            @csrf --}}
                            <input type="hidden" id="csrf" value="{{ csrf_token() }}">
                            <input type="hidden" id="memberid" name="memberid" value="{{ $data->memberid ?? '' }}">

                            <div class="fund-add-field my-3" style="margin-top: 15px; !important">
                                <label for="amount">Request Amount (USDT)</label>
                                <div class="fund-add-input-wrapper">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                    <input id="amount" name="amount" type="number" step="0.01" min="1"
                                        placeholder="Enter amount in USDT" class="fund-add-input"
                                        value="{{ old('amount') }}" required>
                                </div>
                                @error('amount')
                                    <small class="fund-add-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="fund-add-field" style="margin-bottom: 15px; margin-top: 15px; !important">
                                <label for="amount">Fund Wallet Balance</label>
                                <div class="fund-add-input-wrapper">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                    <input id="walletBalance" type="text" class="fund-add-input"
                                        value="{{ number_format((float) $data['p2p_wallet'], 2) > 0 ? number_format((float) $data['p2p_wallet'], 2) : '0.00' }}"
                                        readonly>
                                </div>
                                @error('amount')
                                    <small class="fund-add-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="fund-add-submit-wrapper">
                                <input type="hidden" id="route" value="{{ route('member.addFund') }}">
                                <button id="depositBtn" type="submit" class="fund-add-submit green-button depositBtn">
                                    <span>Proceed with Payment</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                            {{-- </form> --}}
                        </article>
                    </div>

                    <!-- RIGHT COLUMN: PAYMENT INSTRUCTIONS -->
                    <div class="fund-add-column-right">
                        <!-- SECTION 3: PAYMENT INSTRUCTION CARD -->
                        <article class="fund-add-instruction-card glass-surface">
                            <div class="fund-add-instruction-header">
                                <h2><i class="fa-solid fa-circle-info"></i> dApp Deposit Instructions</h2>
                            </div>
                            <div class="fund-add-instruction-body">
                                <ul class="fund-add-instruction-list">
                                    <li>Connect your wallet through the dApp and approve the transaction.</li>
                                    <li>Enter the USDT amount you want to add.</li>
                                    <li>The funds will be sent automatically to the company wallet after confirmation.</li>
                                    <li class="warning-item">Use the correct network and ensure your wallet has enough balance.</li>
                                </ul>

                                <hr class="fund-add-instruction-divider">

                                <div class="fund-add-wallet-info">
                                    <div class="fund-add-wallet-row">
                                        <span class="wallet-label">Wallet Type</span>
                                        <span class="wallet-value">BNB BEP20 / dApp Wallet</span>
                                    </div>
                                    <div class="fund-add-wallet-row">
                                        <span class="wallet-label">Network</span>
                                        <span class="wallet-value">BNB Smart Chain</span>
                                    </div>
                                    <div class="fund-add-wallet-address-section">
                                        <span class="wallet-label">Company Wallet Address</span>
                                        <div class="fund-add-wallet-address-box">
                                            <span class="wallet-address-text"
                                                id="js-wallet-address-text">{{ '0xaF76340E57b573a41e796139e4738C201c869a0b' }}</span>
                                            <button class="fund-add-wallet-copy" id="js-copy-wallet-btn" type="button"
                                                title="Copy Address">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                        <span class="fund-add-wallet-copy-msg" id="js-wallet-copy-msg"
                                            style="display: none; color: var(--gl-green); font-size: 12px; font-weight: 700; margin-top: 5px;">Copied!</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/3.0.0-rc.5/web3.min.js"></script>
    <script src="https://unpkg.com/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.js"></script>
    <script src="{{ asset('member/assets/js/blocks.js') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.fund-add-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = form.querySelector('.fund-add-submit');
                    if (btn) {
                        btn.disabled = true;
                        btn.classList.add('is-loading');
                        const span = btn.querySelector('span');
                        if (span) span.innerText = 'Processing...';
                    }

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (Number(data.code) === 1) {
                                window.location.href = "{{ route('member.add-fund.history') }}";
                            } else {
                                console.log('Add fund failed response:', data);
                                alert(data.message ||
                                    'Failed to add fund. Please check your transaction details.');
                                if (btn) {
                                    btn.disabled = false;
                                    btn.classList.remove('is-loading');
                                    const span = btn.querySelector('span');
                                    if (span) span.innerText = 'Proceed with Payment';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                            if (btn) {
                                btn.disabled = false;
                                btn.classList.remove('is-loading');
                                const span = btn.querySelector('span');
                                if (span) span.innerText = 'Proceed with Payment';
                            }
                        });
                });
            }
        });
    </script>
@endsection
