@extends('member.layouts.app')

@section('title', 'Signup - ' . config('detailsApp.name'))
@section('body_class', 'member-auth-body')

@section('content')
    <main class="member-page auth-page signup-page">
        <button class="theme-toggle floating-theme-toggle js-theme-toggle" type="button" aria-label="Toggle theme">
            <i class="fa-solid fa-sun"></i>
        </button>

        <section class="auth-visual signup-visual">
            <a class="auth-logo" href="{{ route('member.login') }}">
                <img src="{{ asset('member/assets/images/logo-greenlife.png') }}" alt="logo">
            </a>

            <div class="auth-copy">
                <h1>Join the <span>GreenLife Community!</span></h1>
                <p>Create your account and start your journey towards financial freedom and personal growth.</p>
            </div>

            <div class="feature-list detailed-features">
                <div><i class="fa-solid fa-user-group"></i><span><strong>Grow Your Network</strong><small>Build connections
                            worldwide</small></span></div>
                <div><i class="fa-solid fa-money-bill-trend-up"></i><span><strong>Earn Unlimited</strong><small>Multiple
                            income streams</small></span></div>
                <div><i class="fa-solid fa-shield-heart"></i><span><strong>Secure & Trusted</strong><small>Your security is
                            our priority</small></span></div>
                <div><i class="fa-solid fa-headset"></i><span><strong>24/7 Support</strong><small>We are here for you
                            always</small></span></div>
            </div>

            @include('member.layouts.footer')
        </section>

        <style>
            .signup-card,
            .auth-card {
                height: auto !important;
                overflow: visible !important;
            }

            .signup-form-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                column-gap: 14px;
                row-gap: 18px;
                width: 100%;
            }

            .signup-form-field {
                min-width: 0;
                max-width: 100%;
                display: flex;
                flex-direction: column;
            }

            .signup-form-field .field-error {
                margin-top: 7px;
                padding-left: 2px;
                line-height: 1.25;
            }

            .signup-form-field input,
            .signup-form-field select {
                min-width: 0;
                max-width: 100%;
            }

               .signup-form-select option {
                background: #06140b;
                color: #ffffff;
                padding: 10px 16px;
                font-size: 14px;
            }

            .signup-page .signup-form-field select.signup-form-select {
                padding-left: 8px !important;
                padding-right: 34px !important;
                text-indent: 0;
            }

            .signup-page .signup-form-field select.signup-form-select option {
                padding-left: 16px;
                padding-right: 16px;
            }

            @media (max-width: 767px) {
                .signup-form-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <section class="auth-panel-wrap">
            <div class="auth-card signup-card glass-surface">
                <div class="card-leaf-mark"><i class="fa-solid fa-seedling"></i></div>
                <h2>Create Your Account</h2>
                <p>Fill in your details to get started</p>

                @if (session('success'))
                    <div class="glass-alert success">{{ session('success') }}</div>
                @endif
                @if (session('failedMsg'))
                    <div class="glass-alert danger">{{ session('failedMsg') }}</div>
                @endif

                <form class="auth-form signup-form js-form-loading" method="POST"
                    action="{{ route('member.signup.submit') }}">
                    @csrf

                    <div class="signup-form-grid">
                        <!-- Sponsor ID -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-solid fa-link"></i>
                                <input type="text" name="sponsor" id="sponsor"
                                    value="{{ old('sponsor', isset($sdata) && $sdata != '1' ? strtoupper($sdata) : '') }}"
                                    placeholder="Enter Sponsor Id" autocomplete="off"
                                    data-sponsor-check-url="{{ route('member.check-sponsor') }}">
                            </label>
                            @error('sponsor')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                            <span id="sponhtml"></span>
                        </div>

                        <!-- Name -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Name">
                            </label>
                            @error('name')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-regular fa-envelope"></i>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email">
                            </label>
                            @error('email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" name="mobile" minlength="10" maxlength="10"
                                    value="{{ old('mobile') }}" placeholder="Enter Mobile"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </label>
                            @error('mobile')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="signup-form-field">
                            <label class="form-field " style="position: relative;">
                                 <i class="fa-solid fa-globe"></i>
                                <select name="country" class="signup-form-select"
                                    style="width: 100%; border: 0; outline: 0; background: transparent; color: var(--gl-text); font-size: 14px; font-weight: 600; appearance: none; padding-left: 8px; padding-right: 34px; cursor: pointer;">
                                        <option value="">Select Country</option>
                                    @foreach ($cdata ?? [] as $country)
                                        @php
                                            $countryName =
                                                $country->nicename ??
                                                ($country->name ??
                                                    ($country->country_name ?? ($country->country ?? '')));
                                        @endphp
                                        @if ($countryName)
                                            <option value="{{ $countryName }}"
                                                {{ old('country') == $countryName ? 'selected' : '' }}>
                                                {{ $countryName }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <span style="position: absolute; right: 14px; pointer-events: none;"><i
                                        class="fa-solid fa-chevron-down"
                                        style="margin: 0; font-size: 12px; color: var(--gl-muted);"></i></span>
                            </label>
                            @error('country')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Security Pin (txn_password) -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="txn_password" placeholder="Enter Security Pin"
                                    autocomplete="new-password">
                                <button class="password-toggle js-password-toggle" type="button"
                                    aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </label>
                            @error('txn_password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password" placeholder="Enter Password"
                                    autocomplete="new-password">
                                <button class="password-toggle js-password-toggle" type="button"
                                    aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </label>
                            @error('password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="signup-form-field">
                            <label class="form-field ">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                    autocomplete="new-password">
                                <button class="password-toggle js-password-toggle" type="button"
                                    aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </label>
                            @error('password_confirmation')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button class="green-button" type="submit">
                        <span>Create Account</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-switch">Already have an account? <a href="{{ route('member.login') }}">Login</a></div>
            </div>
        </section>
    </main>
@endsection
