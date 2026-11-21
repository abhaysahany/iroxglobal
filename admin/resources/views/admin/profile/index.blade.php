@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Admin Profile</h1>
        </div>
    </div>

    <section class="panel settings-panel">
        @if ($admin?->profile_image)
            <div class="logo-preview">
                <span>Profile Image</span>
                <img src="{{ asset($admin->profile_image) }}" alt="Admin profile">
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name ?? '') }}">
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email ?? '') }}">
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $admin->phone ?? '') }}">
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="profile_image" accept="image/png,image/jpeg,image/webp">
                    @error('profile_image')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions" style="text-align: center;">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>

        </form>
    </section>
@endsection
