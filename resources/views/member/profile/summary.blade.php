@extends('member.layouts.app')
@section('title', 'User Summary - GreenLife Network')
@section('body_class', 'member-dashboard-body')
@section('page_title', 'User Summary')
@section('content')

<style>
    .profile-summary-page .profile-summary-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 16px !important;
        margin-bottom: 16px;
        margin-left: 0;
        margin-right: 0;
    }

    .profile-summary-page .profile-summary-stats-grid > .col-4 {
        width: auto !important;
        max-width: none !important;
        flex: initial !important;
        padding: 0 !important;
    }

    .profile-summary-page .profile-summary-stat-box {
        height: 100%;
    }

    .profile-summary-page .profile-summary-detail-grid {
        display: flex !important;
        justify-content: center;
    }

    .profile-summary-page .profile-summary-column-left {
        width: 100%;
        max-width: 760px;
    }

    .profile-summary-page .profile-summary-section-heading {
        justify-content: center;
        text-align: center;
    }

    .profile-summary-page .profile-summary-section-heading h2 {
        width: 100%;
        text-align: center;
    }
</style>


@php
$profileImageValue = null;
if (is_array($memberData)) {
    $profileImageValue = $memberData['profile_image']
        ?? $memberData['image']
        ?? $memberData['photo']
        ?? $memberData['avatar']
        ?? $memberData['pic']
        ?? $memberData['profile_pic']
        ?? null;
} elseif (is_object($memberData)) {
    $profileImageValue = $memberData->profile_image
        ?? $memberData->image
        ?? $memberData->photo
        ?? $memberData->avatar
        ?? $memberData->pic
        ?? $memberData->profile_pic
        ?? null;
}

$profileImageUrl = null;

if ($profileImageValue) {
    if (\Illuminate\Support\Str::startsWith($profileImageValue, ['http://', 'https://'])) {
        $profileImageUrl = $profileImageValue;
    } elseif (\Illuminate\Support\Str::startsWith($profileImageValue, ['storage/', 'uploads/', 'member/', 'members/'])) {
        $profileImageUrl = asset($profileImageValue);
    } else {
        if (file_exists(public_path('uploads/' . $profileImageValue))) {
            $profileImageUrl = asset('uploads/' . $profileImageValue);
        } else {
            $profileImageUrl = asset('uploads/profile/' . $profileImageValue);
        }
    }
}
@endphp

<main class="member-page dashboard-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content profile-summary-page">
            <!-- 1. PROFILE SUMMARY HERO CARD -->
            <article class="profile-summary-card profile-summary-hero glass-surface">
                <div class="profile-summary-hero-content">
                    <div class="profile-summary-hero-left">
                        <div class="profile-summary-avatar-wrapper">
                            @if ($profileImageUrl)
                                <img src="{{ $profileImageUrl }}" alt="{{ $memberData['name'] ?? $memberData->name ?? 'Profile' }} avatar" class="profile-summary-avatar-img">
                            @else
                                <img src="{{ ($memberData['avatar'] ?? $memberData->avatar ?? null) ? asset($memberData['avatar'] ?? $memberData->avatar) : asset('member/assets/images/user-avatar.png') }}" alt="{{ $memberData['name'] ?? $memberData->name ?? 'Profile' }} avatar" class="profile-summary-avatar-img">
                            @endif
                        </div>
                        <div class="profile-summary-hero-info">
                            <h2>{{ $memberData['name'] }}</h2>
                            <p class="profile-summary-hero-role">{{ $memberData['role'] }}</p>
                            <div class="profile-summary-badges">
                                <span class="profile-summary-badge badge-rank"><i class="fa-solid fa-award"></i> {{ $memberData['rank'] }}</span>
                                <span class="profile-summary-badge badge-status"><i class="fa-solid fa-circle-check"></i> {{ $memberData['status'] }}</span>
                            </div>
                            <div class="profile-summary-meta-row">
                                <span><strong>Member ID:</strong> {{ $memberData['memberid'] }}</span>
                                <span><strong>Joined:</strong> {{ $memberData['created_at'] }}</span>
                                <span><strong>Country:</strong> {{ $memberData['country'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-summary-hero-right">
                        <a href="{{ route('member.profile.edit-profile') }}" class="profile-edit-btn">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Edit Profile</span>
                        </a>
                    </div>
                </div>
            </article>

            <!-- 2. BUSINESS OVERVIEW STATS GRID -->
            <section class="profile-summary-stats-grid row g-3">
                <div class="col-4">
                    <div class="profile-summary-stat-box glass-surface h-100">
                        <div class="profile-summary-stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                        <span class="profile-summary-stat-label">P2p Wallet</span>
                        <strong class="profile-summary-stat-value" data-prefix="$" data-decimals="2">$ {{ $memberData['p2p_wallet'] }}</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="profile-summary-stat-box glass-surface h-100">
                        <div class="profile-summary-stat-icon"><i class="fa-solid fa-wallet"></i></div>
                        <span class="profile-summary-stat-label">Wallet Balance</span>
                        <strong class="profile-summary-stat-value"  data-prefix="$" data-decimals="2">$ {{ $memberData['wallet'] }}</strong>
                    </div>
                </div>
              
                <div class="col-4">
                    <div class="profile-summary-stat-box glass-surface h-100">
                        <div class="profile-summary-stat-icon"><i class="fa-solid fa-users"></i></div>
                        <span class="profile-summary-stat-label">Team Members</span>
                        <strong class="profile-summary-stat-value" >{{ $memberData['team'] }}</strong>
                    </div>
                </div>
              
            </section>

            <!-- TWO-COLUMN DETAIL GRID -->
            <div class="profile-summary-detail-grid">
                <!-- LEFT COLUMN -->
                <div class="profile-summary-column-left">
                    <!-- 3. MEMBER INFORMATION CARD -->
                    <article class="profile-summary-card glass-surface">
                        <div class="profile-summary-section-heading">
                            <h2>Member Information</h2>
                        </div>
                        <div class="profile-summary-info-list" >
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Full Name</span>
                                <span class="profile-summary-info-value">{{ $memberData['name'] }}</span>
                            </div>
                           
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Email Address</span>
                                <span class="profile-summary-info-value">{{ $memberData['email'] }}</span>
                            </div>
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Phone Number</span>
                                <span class="profile-summary-info-value">{{ $memberData['mobile'] }}</span>
                            </div>
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Country</span>
                                <span class="profile-summary-info-value">{{ $memberData['country'] }}</span>
                            </div>
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Sponsor ID</span>
                                <span class="profile-summary-info-value">{{ $memberData['sponsorid'] }}</span>
                            </div>
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Package</span>
                                <span class="profile-summary-info-value">$ {{ $memberData['package'] }}</span>
                            </div>
                            <div class="profile-summary-info-row">
                                <span class="profile-summary-info-label">Account Status</span>
                                <span class="profile-summary-info-value" style="color: var(--gl-green); font-weight: 800;">{{ $memberData['status'] }}</span>
                            </div>
                        </div>
                    </article>
                </div>
               
            </div>
        </div>
    </section>
</main>
@endsection
