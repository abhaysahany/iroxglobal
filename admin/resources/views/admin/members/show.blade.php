@extends('admin.layouts.app')

@section('title', 'Edit Member')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Edit {{ $member->name }}</h1>
        </div>
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Back</a>
    </div>

    <section class="panel settings-panel">
        @if (session('updateErr'))
            <div class="alert alert-success">{{ session('updateErr') }}</div>
        @endif

        @if (session('failedMsg'))
            <div class="alert alert-danger">{{ session('failedMsg') }}</div>
        @endif

        <form action="{{ route('admin.members.update', $member) }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $member->id }}">

            <div class="panel-heading">
                <h2>Profile Details</h2>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}">
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="father">Father Name</label>
                    <input type="text" id="father" name="father" value="{{ old('father', $member->father_name) }}">
                    @error('father')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="{{ old('dob', $member->dob) }}">
                    @error('dob')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $member->mobile) }}">
                    @error('mobile')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" @selected(old('gender', $member->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $member->gender) === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender', $member->gender) === 'Other')>Other</option>
                    </select>
                    @error('gender')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $member->address) }}">
                    @error('address')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $member->city) }}">
                    @error('city')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $member->state) }}">
                    @error('state')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="">Select Country</option>
                        @foreach ($cdata ?? [] as $country)
                            @php
                                $countryName =
                                    $country->nicename ??
                                    ($country->name ?? ($country->country_name ?? ($country->country ?? '')));
                            @endphp
                            @if ($countryName)
                                <option value="{{ $countryName }}" @selected(old('country', $member->country) == $countryName)>
                                    {{ $countryName }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('country')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!--<div class="form-group">-->
                <!--    <label for="image">Profile Image</label>-->
                <!--    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">-->
                <!--    @if ($member->profile_image)-->
                <!--        <small>Current image: {{ $member->profile_image }}</small>-->
                <!--    @endif-->
                <!--    @error('image')-->
                <!--        <span class="error-text">{{ $message }}</span>-->
                <!--    @enderror-->
                <!--</div>-->
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Member</button>
            </div>
        </form>
    </section>
@endsection
