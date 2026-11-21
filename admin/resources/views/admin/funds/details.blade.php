@extends('admin.layouts.app')

@section('title', 'Funds Details')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Funds Management</p>
            <h1>Funds Details</h1>
        </div>
    </div>

    <section class="panel">
        <div class="table-wrap table-responsive">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Date &amp; Time</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Wallet Type</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $transaction->memberid ?? '-' }}</td>
                            <td>{{ $transaction->member?->name ?? '-' }}</td>
                            <td><span
                                    class="status {{ $transaction->type === 'Add' ? 'success' : 'danger' }}">{{ $transaction->type }}</span>
                            </td>
                            <td>{{ number_format((float) $transaction->amount, 2) }}</td>
                            <td>{{ $transaction->wallet_type }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 18px;">No fund transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    </section>
@endsection
