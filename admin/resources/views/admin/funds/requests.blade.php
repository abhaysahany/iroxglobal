@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Funds Management</p>
            <h1>{{ $title }}</h1>
        </div>
    </div>

    @unless ($pendingOnly)
        <section class="panel">
            <form method="GET" action="{{ route('admin.funds.requests.details') }}" class="settings-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Statuses</option>
                            @foreach (['Pending', 'Approved', 'Rejected'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="member_id">Member ID</label>
                        <select name="member_id" id="member_id">
                            <option value="">All Members</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}" @selected((string) request('member_id') === (string) $member->id)>{{ $member->member_id }} - {{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="form-group">
                        <label for="date_to">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="form-actions" style="gap: 10px;">
                    <a href="{{ route('admin.funds.requests.details') }}" class="btn btn-outline">Reset</a>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </section>
    @endunless

    <section class="panel">
        <div class="table-wrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Member ID / User ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        @unless ($pendingOnly)<th>Transaction ID</th>@endunless
                        <th>Screenshot / Proof</th>
                        <th>Status</th>
                        @unless ($pendingOnly)<th>Admin Remark</th>@endunless
                        <th>Date</th>
                        @if ($pendingOnly)<th>Actions</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $fundRequest)
                        <tr>
                            <td>#{{ $fundRequest->id }}</td>
                            <td>{{ $fundRequest->member?->member_id ?? '-' }}</td>
                            <td>{{ $fundRequest->member?->name ?? '-' }}</td>
                            <td>{{ number_format((float) $fundRequest->amount, 2) }}</td>
                            <td>{{ $fundRequest->payment_mode }}</td>
                            @unless ($pendingOnly)<td>{{ $fundRequest->transaction_id ?? '-' }}</td>@endunless
                            <td>
                                @if ($fundRequest->screenshot)
                                    <a href="{{ asset(ltrim($fundRequest->screenshot, '/')) }}" target="_blank" rel="noopener" class="btn btn-outline" style="min-height: 34px; padding: 6px 12px;">View Proof</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="status {{ $fundRequest->status === 'Approved' ? 'success' : ($fundRequest->status === 'Rejected' ? 'danger' : 'warning') }}">{{ $fundRequest->status }}</span></td>
                            @unless ($pendingOnly)<td>{{ $fundRequest->admin_remark ?? '-' }}</td>@endunless
                            <td>{{ $fundRequest->created_at->format('d M Y, h:i A') }}</td>
                            @if ($pendingOnly)
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px; white-space: nowrap;">
                                        <form action="{{ route('admin.funds.requests.approve', $fundRequest) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="min-height: 34px; padding: 6px 12px;" onclick="return confirm('Approve this request and credit the member wallet?')">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.funds.requests.reject', $fundRequest) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline" style="min-height: 34px; padding: 6px 12px;" onclick="return confirm('Reject this fund request?')">Reject</button>
                                        </form>
                                        <a href="{{ route('admin.funds.requests.details', ['request_id' => $fundRequest->id]) }}" class="btn btn-outline" style="min-height: 34px; padding: 6px 12px;">View</a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $pendingOnly ? 10 : 10 }}" style="text-align: center; padding: 18px;">No fund requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $requests->links() }}
    </section>
@endsection
