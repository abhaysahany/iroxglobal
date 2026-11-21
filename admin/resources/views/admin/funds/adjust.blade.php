@extends('admin.layouts.app')

@php
    $isCredit = $type === 'Credit';
    $title = $isCredit ? 'Add Funds' : 'Deduct Funds';
@endphp

@section('title', $title)

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Funds Management</p>
            <h1>{{ $title }}</h1>
        </div>
    </div>

    <section class="panel settings-panel">
        <div class="panel-heading">
            <h2>{{ $isCredit ? 'Credit Member Wallet' : 'Debit Member Wallet' }}</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">Please check the form errors and try again.</div>
        @endif

        <form action="{{ $isCredit ? route('admin.funds.add.submit') : route('admin.funds.deduct.submit') }}" method="POST" class="settings-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="member_id">Member ID / User ID</label>
                    <select name="member_id" id="member_id" required>
                        <option value="">Select Member</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected((string) old('member_id') === (string) $member->id)>
                                {{ $member->member_id }} - {{ $member->name }} (Balance: {{ number_format((float) $member->wallet_balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0.01" step="0.01" placeholder="0.00" required>
                    @error('amount') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="wallet_type">Wallet Type</label>
                    <input type="text" id="wallet_type" value="Main Wallet" readonly>
                </div>

                <div class="form-group">
                    <label for="remark">Remark / Note</label>
                    <textarea name="remark" id="remark" rows="3" maxlength="1000" placeholder="Enter reason for this adjustment" required>{{ old('remark') }}</textarea>
                    @error('remark') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Confirm this {{ strtolower($title) }} transaction?')">
                    <i class="fa-solid {{ $isCredit ? 'fa-circle-plus' : 'fa-circle-minus' }}"></i>&nbsp; {{ $title }}
                </button>
            </div>
        </form>
    </section>
@endsection
