@extends('admin.layouts.app')
@section('title', 'Investments Details')
@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Investments Details</h1>
        </div>
    </div>

    <div class="table-wrap table-responsive">
        <table class="table table-bordered table-striped admin-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Invest Date</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Package</th>
                    <th>Capping</th>
                    <th>Capping Year</th>
                    <th>Invest Amount</th>
                    <th>Paid Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stakingDetails as $list)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td> {{ date('d-m-Y', strtotime($list['invest_date'])) }}</td>
                        <td>{{ $list->memberid }}</td>
                        <td>{{ getName($list->memberid) }}</td>
                        <td>$ {{ $list->package }}</td>
                        <td>{{ $list->capping }}</td>
                        <td>{{ $list->capping_year }}</td>
                        <td>$ {{ number_format((float) $list->invest_amount, 2) }}</td>
                        <td>$ {{ number_format((float) $list->paid_amount, 2) }}</td>
                        <td>
                            @if ($list['status'] == 'Active')
                                <label class="btn btn-sm" style="background-color: #22c55e; color: #ffffff">{{ $list['status'] }}</label>
                            @else
                                <label class="btn btn-sm" style="background-color: #ef4444; color: #ffffff">{{ $list['status'] }}</label>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
