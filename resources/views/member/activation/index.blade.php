@extends('member.layouts.app')

@section('title', 'Activation - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Activation')

@section('content')
    <style>
        .activation-card-wrap {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 24px;
        }

        .activation-card {
            width: 100%;
            max-width: 484px;
            padding: 26px;
            border-radius: 18px;
            border: 1px solid var(--gl-border);
            background: var(--gl-panel);
            overflow-wrap: anywhere;
        }

        .activation-package-badge {
            display: inline-flex;
            padding: 7px 14px;
            border: 1px solid var(--gl-border);
            border-radius: 999px;
            background: rgba(131, 226, 63, 0.08);
            color: var(--gl-green);
            font-size: 12px;
            font-weight: 800;
        }

        .activation-card-heading {
            margin: 20px 0 18px;
            color: var(--gl-gold);
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(26px, 5vw, 34px);
            font-weight: 800;
        }

        .activation-amount-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 14px;
            border-bottom: 1px dotted var(--gl-border);
            color: var(--gl-muted);
            font-size: 14px;
        }

        .activation-amount-row strong {
            color: var(--gl-green);
            font-size: 20px;
            font-weight: 800;
        }

        .activation-card-text {
            min-height: 58px;
            margin: 16px 0 24px;
            color: var(--gl-muted);
            font-size: 14px;
        }

        .activation-field {
            margin-bottom: 12px;
        }

        .activation-field label {
            display: block;
            margin-bottom: 7px;
            color: var(--gl-gold);
            font-size: 12px;
            font-weight: 800;
        }

        .activation-field input {
            width: 100%;
            min-height: 50px;
            padding: 12px 14px;
            border: 1px solid var(--gl-border);
            border-radius: 11px;
            background: rgba(4, 18, 9, 0.55);
            color: var(--gl-text);
            font-size: 16px;
            outline: none;
        }

        .activation-submit {
            width: 100%;
            min-height: 50px;
            margin-top: 6px;
            border: 1px solid var(--gl-green);
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gl-green), #6ab82a);
            color: #000;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .activation-submit:hover {
            transform: translateY(-1px);
        }

        .activation-active-icon {
            display: block;
            margin-bottom: 18px;
            color: var(--gl-green);
            font-size: 48px;
        }

        .activation-card-active {
            padding-block: 48px;
            text-align: center;
        }

        .activation-card-active .activation-card-heading {
            margin-top: 0;
            font-size: clamp(22px, 4vw, 28px);
        }

        .activation-card-active .activation-card-text {
            min-height: 0;
            margin-bottom: 0;
        }

        @media (max-width: 575px) {
            .activation-card {
                padding: 20px;
            }
        }
    </style>

    <main class="member-page dashboard-page activation-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content activation-container">
                @if (session('success'))
                    <div class="glass-alert success dashboard-alert">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="glass-alert danger dashboard-alert">{{ session('error') }}</div>
                @endif

                <section class="activation-card-wrap" aria-label="Account activation">
                    @if ($isActive)
                        <article class="activation-card activation-card-active glass-surface">
                            <i class="fa-solid fa-circle-check activation-active-icon" aria-hidden="true"></i>
                            <h1 class="activation-card-heading">Your Account is Active</h1>
                            <p class="activation-card-text">Your member account has been activated successfully.</p>
                        </article>
                    @else
                        <article class="activation-card glass-surface">
                            <span class="activation-package-badge">PACKAGE</span>
                            <h1 class="activation-card-title">Activate Your Account</h1>
                            <p class="activation-card-subtitle">Unlock your GreenLife member benefits and start growing your network with the selected activation package.</p>

                            <div class="activation-wallet-card">
                                <div class="activation-wallet-icon">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div class="activation-wallet-content">
                                    <span>P2P Wallet</span>
                                    <h3>$ {{ number_format((float)($data->p2p_wallet ?? 0), 2) }} USDT</h3>
                                    <p>Available balance for account activation</p>
                                </div>
                            </div>

                             <form action="{{ route('member.activation.buy') }}" method="POST">
                                 @csrf

                                 @if (session('successMsg'))
                                     <div class="activation-message activation-message-success">
                                         <i class="fa-solid fa-circle-check"></i>
                                         <span>{{ session('successMsg') }}</span>
                                     </div>
                                 @endif

                                 @if (session('failedMsg'))
                                     <div class="activation-message activation-message-error">
                                         <i class="fa-solid fa-triangle-exclamation"></i>
                                         <span>{{ session('failedMsg') }}</span>
                                     </div>
                                 @endif

                                 @if (session('error'))
                                     <div class="activation-message activation-message-error">
                                         <i class="fa-solid fa-triangle-exclamation"></i>
                                         <span>{{ session('error') }}</span>
                                     </div>
                                 @endif

                                 @if (session('status'))
                                     <div class="activation-message activation-message-success">
                                         <i class="fa-solid fa-circle-check"></i>
                                         <span>{{ session('status') }}</span>
                                     </div>
                                 @endif

                                 @if ($errors->any())
                                     <div class="activation-message activation-message-error">
                                         <i class="fa-solid fa-triangle-exclamation"></i>
                                         <div>
                                             @foreach ($errors->all() as $error)
                                                 <div>{{ $error }}</div>
                                             @endforeach
                                         </div>
                                     </div>
                                 @endif

                                 <div class="activation-field">
                                     <label for="activation-package">Package</label>
                                     <input id="activation-package" type="text" value="$70 Package" readonly>
                                     @error('package')
                                         <small class="activation-input-error">{{ $message }}</small>
                                     @enderror
                                 </div>

                                 <div class="activation-field" style="margin-top: 15px;">
                                     <label for="txn-password">Security PIN / Transaction Password</label>
                                     <input id="txn-password" type="password" name="txn_password" placeholder="Enter Security PIN" required>
                                     @error('txn_password')
                                         <small class="activation-input-error">{{ $message }}</small>
                                     @enderror
                                 </div>

                                 <input type="hidden" name="package" value="70">
                                 <input type="hidden" name="registration_amount" value="70.00">
                                 <input type="hidden" name="total_amount" value="70.00">

                                 <button type="submit" class="activation-submit">Activate Now</button>
                             </form>
                        </article>
                    @endif
                </section>
            </div>
        </section>
    </main>
@endsection
