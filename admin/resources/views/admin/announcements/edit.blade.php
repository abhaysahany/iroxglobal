@extends('admin.layouts.app')

@section('title', 'Edit Announcement')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Edit Announcement</h1>
        </div>
    </div>

    <section class="panel">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="settings-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $announcement->title) }}">
                @error('title') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="5">{{ old('message', $announcement->message) }}</textarea>
                @error('message') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Active" @selected(old('status', $announcement->status) === 'Active')>Active</option>
                    <option value="Inactive" @selected(old('status', $announcement->status) === 'Inactive')>Inactive</option>
                </select>
                @error('status') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </section>
@endsection
