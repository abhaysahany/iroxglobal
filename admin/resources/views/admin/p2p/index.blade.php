@extends('admin.layouts.app')

@section('title', 'P2P')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>P2P Transactions</h1>
        </div>
    </div>

    <section class="panel settings-panel">
        <div class="panel-heading"><h2>Create Manual P2P</h2></div>
        <form action="{{ route('admin.p2p.store') }}" method="POST" class="settings-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Sender</label>
                    <select name="sender_member_id">
                        <option value="">Select Sender</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->member_id }} - {{ $member->name }} ({{ number_format($member->wallet_balance, 2) }})</option>
                        @endforeach
                    </select>
                    @error('sender_member_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Receiver</label>
                    <select name="receiver_member_id">
                        <option value="">Select Receiver</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->member_id }} - {{ $member->name }}</option>
                        @endforeach
                    </select>
                    @error('receiver_member_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="form-group"><label>Amount</label><input type="number" step="0.01" name="amount">@error('amount') <span class="error-text">{{ $message }}</span> @enderror</div>
                <div class="form-group"><label>Note</label><input type="text" name="transaction_note"></div>
            </div>
            <div class="form-actions"><button class="btn btn-primary">Create P2P</button></div>
        </form>
    </section>

    <section class="panel">
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead><tr><th>Sender</th><th>Receiver</th><th>Amount</th><th>Status</th><th>Note</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->sender?->name ?? '-' }}</td>
                            <td>{{ $transaction->receiver?->name ?? '-' }}</td>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                            <td><span class="status {{ $transaction->status === 'Success' ? 'success' : 'warning' }}">{{ $transaction->status }}</span></td>
                            <td>{{ $transaction->transaction_note ?? '-' }}</td>
                            <td>{{ $transaction->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No P2P transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    </section>
@endsection
