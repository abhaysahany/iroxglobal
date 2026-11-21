@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')
    <style>
        .global-count-card {
            width: 50%;
            max-width: 50%;
            padding: 24px;
        }

        .global-count-card-head {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .global-count-card-head h2 {
            margin: 0 0 6px;
            color: var(--text-dark);
            font-size: 20px;
        }

        .global-count-card-head p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .global-count-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 18px;
        }

        .global-count-input {
            grid-column: span 6;
        }

        .global-count-input input {
            max-width: 100%;
        }

        .global-count-actions {
            justify-content: flex-end;
            padding-top: 2px;
        }

        @media (max-width: 900px) {
            .global-count-card {
                width: 100%;
                max-width: 100%;
            }

            .global-count-input {
                grid-column: 1 / -1;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Global Weekly Count</h1>
        </div>
        <div class="breadcrumb">
            <span>Home</span>
            <span>/</span>
            <strong>Global Weekly Count</strong>
        </div>
    </div>

    <section class="panel settings-panel global-count-card col-6">
        <div class="global-count-card-head">
            <h2>Weekly Count Settings</h2>
            <p>Set sponsor global weekly count value.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                Please check the form errors and try again.
            </div>
        @endif

        <form action="{{ route('admin.count.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            <div class="global-count-grid">
                <div class="form-group global-count-input col-6">
                    <label for="set_weekly_count">Sponsor Global Weekly Count</label>
                    <input type="number" id="set_weekly_count" name="set_weekly_count"
                        value="{{ old('set_weekly_count', $setting->set_weekly_count ?? '') }}" placeholder="value in days">
                    @error('set_weekly_count')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions global-count-actions">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>

        </form>
    </section>
@endsection
