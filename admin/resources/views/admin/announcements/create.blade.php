@extends('admin.layouts.app')

@section('title', 'Add Announcement')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Add Announcement</h1>
        </div>
    </div>

    <section class="panel">
        <form action="{{ route('admin.announcements.store') }}" method="POST" class="settings-form">
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}">
                @error('title') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="5">{{ old('message') }}</textarea>
                @error('message') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                @error('status') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </section>
@endsection
