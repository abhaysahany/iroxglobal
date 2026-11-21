@extends('member.layouts.app')

@section('title', 'Edit Profile - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Edit Profile')

@section('content')
    <style>
        .profile-edit-page .profile-edit-input-file {
            width: 100%;
            min-height: 52px;
            border-radius: 14px;
            border: 1px solid var(--gl-border);
            background: var(--gl-input);
            color: var(--gl-text);
            padding: 12px 14px;
            outline: none;
            font-size: 14px;
            cursor: pointer;
            transition: border-color 0.22s, box-shadow 0.22s;
        }

        .profile-edit-page .profile-edit-input-file:focus {
            border-color: var(--gl-green);
            box-shadow: 0 0 0 3px var(--gl-glow);
        }

        .profile-edit-page .profile-edit-input-file::file-selector-button {
            min-height: 34px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gl-green), var(--gl-green-dark));
            color: #fff;
            font-weight: 700;
            padding: 0 14px;
            margin-right: 12px;
            cursor: pointer;
        }

        .profile-edit-page .profile-edit-submit-wrap {
            width: 100%;
            display: flex !important;
            justify-content: flex-start !important;
            align-items: flex-end !important;
            align-self: flex-start !important;
            text-align: left;
            margin-top: 22px;
            margin-left: 0;
            margin-right: 0;
        }

        .profile-edit-page .profile-edit-submit-wrap .profile-edit-btn,
        .profile-edit-page .profile-edit-submit-wrap .profile-edit-btn-secondary {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        @media (max-width: 768px) {
            .profile-edit-page .profile-edit-submit-wrap {
                margin-top: 0;
            }
        }
    </style>
    <main class="member-page dashboard-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content profile-edit-page">
                @if (session('successMsg'))
                    <div class="glass-alert success dashboard-alert">{{ session('successMsg') }}</div>
                @endif

                @if (session('failedMsg'))
                    <div class="glass-alert danger dashboard-alert">{{ session('failedMsg') }}</div>
                @endif

                @if ($errors->any())
                    <div class="glass-alert danger dashboard-alert">
                        <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- HERO CARD -->
                <article class="profile-edit-hero glass-surface">
                    <div class="profile-edit-hero-content">
                        <span class="profile-edit-pill">MY PROFILE</span>
                        <h1>Edit Profile</h1>
                        <p>Update your profile details.</p>
                    </div>
                </article>

                <!-- FORM CARD -->
                <div class="profile-edit-layout">
                    <div class="profile-edit-column">
                        <article class="profile-edit-card glass-surface">
                            <div class="profile-edit-card-header">
                                <h2>Profile Details</h2>
                            </div>

                            <form action="{{ route('member.profile.edit-profile.update') }}" method="POST"
                                enctype="multipart/form-data" class="js-form-loading profile-edit-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $memberData->id }}">

                                <div class="profile-edit-grid">
                                    <div class="profile-edit-field">
                                        <label for="name">Full Name</label>
                                        <input id="name" type="text" name="name" class="profile-edit-input"
                                            value="{{ old('name', $memberData->name ?? '') }}" readonly
                                            placeholder="Enter full name">
                                        @error('name')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="profile-edit-field">
                                        <label for="email">Email Address</label>
                                        <input id="email" type="email" name="email" class="profile-edit-input"
                                            value="{{ old('email', $memberData->email ?? '') }}" readonly
                                            placeholder="Enter email address">
                                        @error('email')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="profile-edit-field">
                                        <label for="mobile">Phone Number</label>
                                        <input id="mobile" type="text" name="mobile" class="profile-edit-input"
                                            value="{{ old('mobile', $memberData->mobile ?? '') }}" readonly
                                            placeholder="Enter phone number">
                                        @error('mobile')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="profile-edit-field">
                                        <label for="gender">Gender</label>
                                        @if ($memberData->gender == null)
                                            <select id="gender" name="gender" class="profile-edit-select">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        @else
                                            <select id="gender" name="gender" class="profile-edit-select" readonly>
                                                <option value="{{ $memberData->gender }}">{{ $memberData->gender }}
                                                </option>
                                            </select>
                                        @endif
                                        @error('gender')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="profile-edit-field">
                                        <label for="dob">Date of Birth</label>
                                        @if ($memberData->dob == null)
                                            <input id="dob" type="date" name="dob" class="profile-edit-input"
                                                value="{{ old('dob', $memberData->dob ?? '') }}">
                                        @else
                                            <input id="dob" type="date" name="dob" class="profile-edit-input"
                                                value="{{ old('dob', $memberData->dob ?? '') }}" readonly>
                                        @endif
                                        @error('dob')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="profile-edit-field">
                                        <label for="country">Country</label>
                                        <select id="country" name="country" class="profile-edit-select">
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
                                                        {{ old('country', $memberData->country ?? '') == $countryName ? 'selected' : '' }}>
                                                        {{ $countryName }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="profile-edit-field">
                                        <label for="profile_image">Profile Image</label>
                                        <input id="profile_image" type="file" name="profile_image"
                                            class="profile-edit-input-file" accept="image/*">
                                        @if (!empty($memberData->profile_image))
                                            <div class="profile-edit-image-preview">
                                                <span class="preview-label">Current:</span>
                                                <a href="{{ asset('uploads/' . $memberData->profile_image) }}"
                                                    target="_blank"
                                                    class="preview-link">{{ $memberData->profile_image }}</a>
                                            </div>
                                        @endif
                                        @error('profile_image')
                                            <small class="profile-edit-error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="profile-edit-submit-wrap">
                                        <button type="submit" class="profile-edit-btn">
                                            <span>Update Profile</span>
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                        <a href="{{ route('member.profile.summary') }}"
                                            class="profile-edit-btn-secondary">
                                            <i class="fa-solid fa-arrow-left"></i>
                                            <span>Back to User Summary</span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
