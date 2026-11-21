@extends('admin.layouts.app')

@section('title', 'Referral Income')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Referral Income</h1>
        </div>
    </div>

    <div class="table-wrap table-responsive">
        <table class="table table-bordered table-striped admin-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Date</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Package</th>
                    <th>Activating Id</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($incomes as $list)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                        <td>{{ $list->memberid }}</td>
                        <td>{{ getName($list->memberid) }}</td>
                        <td>$ {{ $list->package ?? 'N/A' }}</td>
                        <td>{{ $list->activatingId ?? 'N/A' }}</td>
                        <td>{{ $list->name }}</td>
                        <td>$ {{ number_format((float) $list->amount, 2) }}</td>
                        <td>
                            @if ($list['status'] == 'Paid')
                                <label class="btn btn-sm" style="background-color: #22c55e; color: #ffffff">{{ $list['status'] }}</label>
                            @else
                                <label class="btn btn-sm" style="background-color: #ef4444; color: #ffffff">{{ $list['status'] }}</label>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
