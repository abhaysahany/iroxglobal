@extends('member.layouts.app')

@section('title', 'Forgot Password - ' . config('detailsApp.name'))
@section('body_class', 'member-auth-body')

@section('content')
    <main class="member-page auth-page login-page forgot-password-page">
        <button class="theme-toggle floating-theme-toggle js-theme-toggle" type="button" aria-label="Toggle theme">
            <i class="fa-solid fa-sun"></i>
        </button>

        <section class="auth-visual">
            <a class="auth-logo" href="{{ route('member.login') }}">
                <img src="{{ asset('member/assets/images/logo-greenlife.png') }}" alt="logo">
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
                <div class="card-leaf-mark"><i class="fa-solid fa-seedling"></i></div>
                <h2>Forgot Password?</h2>
                <p>Enter your Member ID and we will send a new password to your registered email.</p>

                <style>
                    .auth-alert {
                        margin-bottom: 16px;
                        padding: 12px 14px;
                        border-radius: 12px;
                    }
                </style>

                @if (session('successMsg'))
                    <div class="glass-alert success auth-alert">
                        {{ session('successMsg') }}
                    </div>
                @endif

                @if (session('failedMsg'))
                    <div class="glass-alert danger auth-alert">
                        {{ session('failedMsg') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="glass-alert danger auth-alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form class="auth-form js-form-loading" method="POST"
                    action="{{ route('member.forgot-password.submit') }}">
                    @csrf
                    <label class="form-field">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="text" name="memberid" value="{{ old('memberid') }}" placeholder="Enter Member ID"
                            required autocomplete="off">
                    </label>
                    @error('memberid')
                        <span class="field-error">{{ $message }}</span>
                    @enderror

                    <button class="green-button" type="submit" style="margin-top: 20px;">
                        <span>Send New Password</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-switch" style="margin-top: 24px;"><a href="{{ route('member.login') }}">Back to Login</a>
                </div>
            </div>
        </section>
    </main>
@endsection
