@extends('admin.layouts.app')

@section('title', 'Withdrawals')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Withdrawal Requests</h1>
        </div>
    </div>

    <section class="panel">

        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Account</th>
                        <th>Status</th>
                        <th>Remark / Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->member?->name ?? '-' }}</td>
                            <td>{{ number_format($withdrawal->amount, 2) }}</td>
                            <td>{{ $withdrawal->payment_method }}</td>
                            <td>{{ $withdrawal->account_details ?? '-' }}</td>
                            <td><span
                                    class="status {{ $withdrawal->status === 'Approved' ? 'success' : ($withdrawal->status === 'Rejected' ? 'danger' : 'warning') }}">{{ $withdrawal->status }}</span>
                            </td>
                            <td>
                                @if ($withdrawal->status === 'Pending')
                                    <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST"
                                        class="settings-form">
                                        @csrf
                                        <input type="text" name="admin_remark" placeholder="Remark">
                                        <button class="btn btn-primary">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST"
                                        class="settings-form">
                                        @csrf
                                        <input type="text" name="admin_remark" placeholder="Remark">
                                        <button class="btn btn-outline">Reject</button>
                                    </form>
                                @else
                                    {{ $withdrawal->admin_remark ?? '-' }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No withdrawals found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $withdrawals->links() }}
    </section>
@endsection
