@extends('admin.layouts.app')

@section('title', 'Rank Income - Manual Transfer')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Rank Income - Manual Transfer</h1>
        </div>
        <a href="{{ route('admin.incomes.rank') }}" class="btn btn-outline">View Rank Income</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="panel settings-panel" style="max-width: 900px;">
        <div class="panel-heading">
            <div>
                <h2>Transfer Rank Income to Member ID</h2>
                <p style="margin: 6px 0 0; color: var(--muted);">
                    Paid income is immediately added to the member wallet. Pending income only creates a record.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.incomes.rank.manual-transfer.store') }}" method="POST" class="settings-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="memberid">Member ID</label>
                    <input type="text" id="memberid" name="memberid" value="{{ old('memberid') }}"
                        data-member-name-target="#name"
                        placeholder="Enter Member ID" required autofocus>
                    <span id="memMsg"></span>
                    @error('memberid') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Member name will appear automatically" readonly>
                    @error('name') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="rank">Rank</label>
                    <input type="text" id="rank" name="rank" value="{{ old('rank') }}"
                        placeholder="Example: R1" required>
                    @error('rank') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}"
                        min="0.01" step="0.01" placeholder="0.00" required>
                    @error('amount') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="installment">Installment</label>
                    <input type="number" id="installment" name="installment" value="{{ old('installment', 1) }}"
                        min="0" step="1" required>
                    @error('installment') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Paid" @selected(old('status', 'Paid') === 'Paid')>Paid</option>
                    </select>
                    @error('status') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Transfer Rank Income
                </button>
            </div>
        </form>
    </section>
@endsection
