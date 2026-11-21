@extends('member.layouts.app')
@section('title', 'Login - ' . config('detailsApp.name'))
@section('body_class', 'member-auth-body')

@section('content')
    <main class="member-page auth-page login-page">
        <button class="theme-toggle floating-theme-toggle js-theme-toggle" type="button" aria-label="Toggle theme">
            <i class="fa-solid fa-sun"></i>
        </button>

        <section class="auth-visual">
            <a class="auth-logo" href="{{ route('member.login') }}">
                <img src="{{ asset('member/assets/images/logo-greenlife.png') }}" alt="GreenLife Network">
            </a>

            <div class="auth-copy">
                <h1>Welcome <span>Back!</span></h1>
                <p>Let's grow your network together.</p>
            </div>

            <div class="feature-list compact-features">
                <div><i class="fa-solid fa-shield-halved"></i><span>Secure Platform</span></div>
                <div><i class="fa-solid fa-earth-americas"></i><span>Global Community</span></div>
                <div><i class="fa-solid fa-seedling"></i><span>Endless Growth</span></div>
                <div><i class="fa-solid fa-award"></i><span>Trusted & Verified</span></div>
            </div>

            <div class="auth-stats glass-surface">
                <div><i class="fa-solid fa-users"></i><strong>25K+</strong><span>Members</span></div>
                <div><i class="fa-solid fa-globe"></i><strong>120+</strong><span>Countries</span></div>
                <div><i class="fa-solid fa-coins"></i><strong>98M+</strong><span>Total Payouts</span></div>
                <div><i class="fa-solid fa-seedling"></i><strong>10Y+</strong><span>Journey</span></div>
            </div>

            @include('member.layouts.footer')
        </section>

        <section class="auth-panel-wrap">
            <div class="auth-card glass-surface">
                <a class="auth-logo" href="{{ route('member.login') }}">
                    <img src="{{ asset('member/assets/images/logo-greenlife.png') }}" alt="logo">
                </a>
                <h2>Login to Your Account</h2>
                <p>Enter your credentials to continue</p>

                <style>
                    .auth-alert {
                        margin-bottom: 16px;
                        padding: 12px 14px;
                        border-radius: 12px;
                    }
                </style>

                @if (session('failedMsg'))
                    <div class="glass-alert danger auth-alert">
                        {{ session('failedMsg') }}
                    </div>
                @endif

                @if (session('failedmsg'))
                    <div class="glass-alert danger auth-alert">
                        {{ session('failedmsg') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="glass-alert success auth-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="glass-alert danger auth-alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form class="auth-form js-form-loading" method="POST" action="{{ route('member.login.submit') }}">
                    @csrf
                    <label class="form-field">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="memberid" name="memberid" value="{{ old('memberid') }}" placeholder="Enter Member ID"
                            autocomplete="memberid">
                    </label>
                    @error('memberid')
                        <span class="field-error">{{ $message }}</span>
                    @enderror

                    <label class="form-field password-field">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" autocomplete="current-password">
                        <button class="password-toggle js-password-toggle" type="button" aria-label="Show password">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </label>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror

                    <div class="form-options">
                        <label class="check-field">
                            <input type="checkbox" name="remember" value="1">
                            <span>Remember Me</span>
                        </label>
                        <a href="{{ route('member.forgot-password') }}">Forgot Password?</a>
                    </div>

                    <button class="green-button" type="submit">
                        <span>Login Now</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <!--<div class="divider"><span>Or continue with</span></div>-->
                <!--<div class="social-row">-->
                <!--    <button type="button" aria-label="Google"><i class="fa-brands fa-google"></i></button>-->
                <!--    <button type="button" aria-label="Apple"><i class="fa-brands fa-apple"></i></button>-->
                <!--    <button type="button" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></button>-->
                <!--    <button type="button" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></button>-->
                <!--</div>-->

                <div class="auth-switch">Don't have an account? <a href="{{ route('member.signup') }}">Sign Up</a></div>
            </div>
        </section>
    </main>
@endsection
