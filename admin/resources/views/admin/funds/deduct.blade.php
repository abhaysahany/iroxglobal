@extends('admin.layouts.app')

@section('title', 'Deduct Funds')

@section('content')
    <style>
        .deduct-funds-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .deduct-funds-heading-icon {
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

        .deduct-funds-heading h1 {
            margin-bottom: 4px;
        }

        .deduct-funds-heading p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .deduct-funds-card {
            width: min(100%, 820px);
            padding: 0;
            overflow: hidden;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .deduct-funds-card .panel-heading {
            margin: 0;
            padding: 18px 22px;
            border-bottom: 1px solid #eef1f6;
        }

        .deduct-funds-card .settings-form {
            padding: 24px 22px;
        }

        .deduct-funds-card .form-grid {
            grid-template-columns: 1fr;
        }

        .deduct-funds-details-button,
        .deduct-funds-submit {
            background: #0d6efd;
            color: #ffffff;
        }

        .deduct-funds-details-button:hover,
        .deduct-funds-submit:hover {
            background: #0b5ed7;
            color: #ffffff;
        }

        @media (max-width: 760px) {
            .deduct-funds-page-header {
                align-items: flex-start;
            }

            .deduct-funds-card .panel-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    <div class="page-header deduct-funds-page-header">
        <div class="deduct-funds-heading">
            <span class="deduct-funds-heading-icon"><i class="fa-solid fa-wallet"></i></span>
            <div>
                <h1>Deduct Funds</h1>
                <p>All Deduct Funds</p>
            </div>
        </div>

        <div class="breadcrumb">
            <span><i class="fa-solid fa-house"></i></span>
            <span>/</span>
            <span>Admin</span>
            <span>/</span>
            <strong>Deduct Funds</strong>
        </div>
    </div>

    <section class="panel deduct-funds-card">
        <div class="panel-heading">
            <h2>Deduct Funds</h2>
            <a href="{{ route('admin.funds.details') }}" class="btn deduct-funds-details-button">Funds Details</a>
        </div>

        <form action="{{ route('admin.funds.deduct.submit') }}" method="POST" class="settings-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="memberid">Memberid</label>
                    <input type="text" name="memberid" id="memberid" value="{{ old('memberid') }}" placeholder="Enter Memberid" required>
                    <span id="memMsg"></span>
                    @error('memberid') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="wallet_type">Wallet Type</label>
                    <select name="wallet_type" id="wallet_type" required>
                        <option value="">Select Wallet Type</option>
                        @foreach ($walletTypes as $walletValue => $walletType)
                            <option value="{{ $walletValue }}" @selected(old('wallet_type') === $walletValue)>{{ $walletType }}</option>
                        @endforeach
                    </select>
                    @error('wallet_type') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0.01" step="0.01" placeholder="Enter $ amount to deduct" required>
                    @error('amount') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn deduct-funds-submit">Submit</button>
            </div>
        </form>
    </section>
@endsection
