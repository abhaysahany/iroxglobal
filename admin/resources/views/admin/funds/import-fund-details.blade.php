@extends('admin.layouts.app')
@section('title', 'Import Fund Details')
@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Import Fund Details</h1>
        </div>
    </div>

    <div class="table-wrap table-responsive">
        <table class="table table-bordered table-striped admin-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Date</th>
                    <th>Member ID</th>
                    <th>Txn Id</th>
                    <th>Order Id</th>
                    <th>Type</th>
                    <th>Wallet Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fund as $list)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                        <td>{{ $list->memberid }}</td>
                        <td>{{ $list->txnid }}</td>
                        <td>{{ $list->orderid }}</td>
                        <td>{{ $list->type }}</td>
                        <td>{{ $list->wallet_type }}</td>
                        <td>$ {{ number_format((float) $list->amount, 2) }}</td>
                        <td>
                            @if ($list['status'] == 'Approved')
                                <span class="btn btn-sm" style="background-color: #22c55e; color: #ffffff">{{ $list['status'] }}</span>
                            @elseif ($list['status'] == 'Pending')
                                <span class="btn btn-sm" style="background-color: #3b82f6; color: #ffffff">{{ $list['status'] }}</span>
                            @else
                                <span class="btn btn-sm" style="background-color: #ef4444; color: #ffffff">{{ $list['status'] }}</span>
                            @endif
                        </td>
                       
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
