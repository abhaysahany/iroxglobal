@extends('admin.layouts.app')

@section('title', 'Add Funds')

@section('content')
    <style>
        .add-funds-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .add-funds-heading-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 50px;
            width: 50px;
            height: 50px;
            border: 1px solid #e9edf5;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 5px 14px rgba(15, 23, 42, 0.08);
            color: var(--primary);
            font-size: 20px;
        }

        .add-funds-heading h1 {
            margin-bottom: 4px;
        }

        .add-funds-heading p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .add-funds-card {
            width: min(100%, 820px);
            padding: 0;
            overflow: hidden;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .add-funds-card .panel-heading {
            margin: 0;
            padding: 18px 22px;
            border-bottom: 1px solid #eef1f6;
        }

        .add-funds-card .settings-form {
            padding: 24px 22px;
        }

        .add-funds-card .form-grid {
            grid-template-columns: 1fr;
        }

        .add-funds-details-button,
        .add-funds-submit {
            background: #0d6efd;
            color: #ffffff;
        }

        .add-funds-details-button:hover,
        .add-funds-submit:hover {
            background: #0b5ed7;
            color: #ffffff;
        }

        @media (max-width: 760px) {
            .add-funds-page-header {
                align-items: flex-start;
            }

            .add-funds-card .panel-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    <div class="page-header add-funds-page-header">
        <div class="add-funds-heading">
            <span class="add-funds-heading-icon"><i class="fa-solid fa-wallet"></i></span>
            <div>
                <h1>Add Funds</h1>
                <p>All Add Funds</p>
            </div>
        </div>

        <div class="breadcrumb">
            <span><i class="fa-solid fa-house"></i></span>
            <span>/</span>
            <span>Admin</span>
            <span>/</span>
            <strong>Add Funds</strong>
        </div>
    </div>

    <section class="panel add-funds-card">
        <div class="panel-heading">
            <h2>Add Funds</h2>
            <a href="{{ route('admin.funds.details') }}" class="btn add-funds-details-button">Funds Details</a>
        </div>

        <form action="{{ route('admin.funds.add.submit') }}" method="POST" class="settings-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="memberid">Memberid</label>
                    <input type="text" name="memberid" id="memberid" placeholder="Enter Memberid" required>
                    <span id="memMsg"></span>
                    @error('memberid')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="wallet_type">Wallet Type</label>
                    <select name="wallet_type" id="wallet_type" required>
                        <option value="">Select Wallet Type</option>
                        @foreach ($walletTypes as $walletValue => $walletType)
                            <option value="{{ $walletValue }}" @selected(old('wallet_type') === $walletValue)>{{ $walletType }}</option>
                        @endforeach
                    </select>
                    @error('wallet_type')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0.01"
                        step="0.01" placeholder="Enter $ amount to add" required>
                    @error('amount')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <input type="hidden" id="csrf" value="{{ csrf_token() }}">
                <button type="submit" class="btn add-funds-submit">Submit</button>
            </div>
        </form>
    </section>
@endsection
