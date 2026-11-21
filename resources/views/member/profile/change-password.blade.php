@extends('member.layouts.app')

@section('title', 'Change Password - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Change Password')

@section('content')
    <main class="member-page dashboard-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content profile-password-page">
                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger alert-dismissible fade show"
                        style="color: red !important; border-color: red !important;">
                        <strong>Error!</strong> {{ session('failedMsg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session()->has('successMsg_pass'))
                    <div class="alert alert-success" role="alert">
                        {{ session('successMsg_pass') }}
                    </div>
                @endif

                <!-- TWO-COLUMN LAYOUT -->
                <div class="profile-password-layout">
                    <!-- LEFT COLUMN: FORM CARD -->
                    <div class="profile-password-column-left">
                        <article class="profile-password-card glass-surface">
                            <div class="profile-password-card-header">
                                <h2>Update Password</h2>
                                <p>Keep your {{config('detailsApp.name')}} account secure by using a strong password.</p>
                            </div>

                              <form action="{{ route('member.profile.change-password.update') }}" method="POST"
                                class="js-form-loading profile-password-form">
                                @csrf

                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <div class="profile-password-field">
                                    <label for="password">New Password</label>
                                    <div class="profile-password-input-wrapper">
                                        <i class="fa-solid fa-lock field-icon"></i>
                                        <input id="password" type="password" name="password" class="profile-password-input"
                                            placeholder="Enter new password">
                                        <button class="password-toggle js-password-toggle" type="button"
                                            aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>

                                    @if ($errors->pass_errors->has('password'))
                                        <small
                                            class="profile-password-error">{{ $errors->pass_errors->first('password') }}</small>
                                    @endif
                                </div>

                                <div class="profile-password-field">
                                    <label for="password_confirm">Confirm New Password</label>
                                    <div class="profile-password-input-wrapper">
                                        <i class="fa-solid fa-shield field-icon"></i>
                                        <input id="password_confirm" type="password" name="password_confirm"
                                            class="profile-password-input" placeholder="Confirm new password">
                                        <button class="password-toggle js-password-toggle" type="button"
                                            aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>

                                    @if ($errors->pass_errors->has('password_confirm'))
                                        <small
                                            class="profile-password-error">{{ $errors->pass_errors->first('password_confirm') }}</small>
                                    @endif
                                </div>

                                <div class="profile-password-submit-wrap">
                                    <button type="submit" class="profile-password-btn">
                                        <span>Update Password</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </article>

                        <!-- TRANSACTION PASSWORD CHANGE CARD -->
                        <article class="profile-password-card glass-surface" style="margin-top: 24px;">
                            <div class="profile-password-card-header">
                                <h2>Change Transaction Password</h2>
                                <p>Update your security pin / transaction password.</p>
                            </div>

                            @if (session()->has('successMsg') && session('successMsg') === 'Transaction Password has been updated successfully')
                                <div class="alert alert-success" role="alert" style="margin-bottom: 20px;">
                                    {{ session('successMsg') }}
                                </div>
                            @endif

                            <form action="{{ route('member.profile.change-transaction-password.update') }}" method="POST"
                                class="js-form-loading profile-password-form">
                                @csrf

                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <div class="profile-password-field">
                                    <label for="txn_password">New Transaction Password</label>
                                    <div class="profile-password-input-wrapper">
                                        <i class="fa-solid fa-lock field-icon"></i>
                                        <input id="txn_password" type="password" name="password" class="profile-password-input"
                                            placeholder="Enter new transaction password">
                                        <button class="password-toggle js-password-toggle" type="button"
                                            aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>

                                    @if ($errors->txn_errors->has('password'))
                                        <small
                                            class="profile-password-error">{{ $errors->txn_errors->first('password') }}</small>
                                    @endif
                                </div>

                                <div class="profile-password-field">
                                    <label for="txn_password_confirm">Confirm Transaction Password</label>
                                    <div class="profile-password-input-wrapper">
                                        <i class="fa-solid fa-shield field-icon"></i>
                                        <input id="txn_password_confirm" type="password" name="password_confirm"
                                            class="profile-password-input" placeholder="Confirm new transaction password">
                                        <button class="password-toggle js-password-toggle" type="button"
                                            aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>

                                    @if ($errors->txn_errors->has('password_confirm'))
                                        <small
                                            class="profile-password-error">{{ $errors->txn_errors->first('password_confirm') }}</small>
                                    @endif
                                </div>

                                <div class="profile-password-submit-wrap">
                                    <button type="submit" class="profile-password-btn">
                                        <span>Update Transaction Password</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </article>
                    </div>

                    <!-- RIGHT COLUMN: INFO CARDS -->
                    <div class="profile-password-column-right">
                        <!-- SECURITY TIPS CARD -->
                        <article class="profile-password-card glass-surface">
                            <div class="profile-password-card-header">
                                <h2>Security Tips</h2>
                            </div>
                            <ul class="profile-password-tips">
                                <li><i class="fa-solid fa-circle-check"></i> Use at least 6 characters</li>
                                <li><i class="fa-solid fa-circle-check"></i> Mix uppercase, lowercase, numbers, and symbols
                                </li>
                                <li><i class="fa-solid fa-circle-check"></i> Do not use your name or email as password</li>
                                <li><i class="fa-solid fa-circle-check"></i> Change your password regularly</li>
                                <li><i class="fa-solid fa-circle-check"></i> Never share your password with anyone</li>
                            </ul>
                        </article>

                        <!-- LAST LOGIN CARD -->
                        <!--<article class="profile-password-card glass-surface">-->
                        <!--    <div class="profile-password-card-header">-->
                        <!--        <h2>Last Login</h2>-->
                        <!--    </div>-->
                        <!--    <div class="profile-password-security-list">-->
                        <!--        <div class="profile-password-security-item">-->
                        <!--            <i class="fa-solid fa-clock"></i>-->
                        <!--            {{-- <span><strong>Last Login:</strong> {{ $security['last_login'] }}</span> --}}-->
                        <!--        </div>-->
                        <!--        <div class="profile-password-security-item">-->
                        <!--            <i class="fa-solid fa-laptop"></i>-->
                        <!--            {{-- <span><strong>Device:</strong> {{ $security['device'] }}</span> --}}-->
                        <!--        </div>-->
                        <!--        <div class="profile-password-security-item">-->
                        <!--            <i class="fa-solid fa-shield-halved"></i>-->
                        <!--            {{-- <span><strong>IP Status:</strong> <span class="profile-password-status-badge success">{{ $security['ip_status'] }}</span></span> --}}-->
                        <!--        </div>-->
                        <!--        <div class="profile-password-security-item">-->
                        <!--            <i class="fa-solid fa-earth-americas"></i>-->
                        <!--            {{-- <span><strong>Location:</strong> {{ $security['location'] }}</span> --}}-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</article>-->

                        <!-- 2FA STATUS CARD -->
                        <!--<article class="profile-password-card glass-surface">-->
                        <!--    <div class="profile-password-card-header">-->
                        <!--        <h2>2FA Authentication</h2>-->
                        <!--    </div>-->
                        <!--    <div class="profile-password-security-list">-->
                        <!--        <div class="profile-password-security-item" style="justify-content: space-between;">-->
                        <!--            <span>2FA Status</span>-->
                        <!--            {{-- <span class="profile-password-status-badge warning">{{ $security['two_factor'] }}</span> --}}-->
                        <!--        </div>-->
                        <!--        <p-->
                        <!--            style="font-size: 13px; color: var(--gl-muted); margin: 6px 0 12px; font-weight: 600; line-height: 1.4;">-->
                        <!--            Enable two-factor authentication for extra protection.-->
                        <!--        </p>-->
                        <!--        <a href="#" class="profile-password-btn-link disabled" onclick="return false;">Coming-->
                        <!--            Soon</a>-->
                        <!--    </div>-->
                        <!--</article>-->
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
