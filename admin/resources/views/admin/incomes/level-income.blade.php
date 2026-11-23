@extends('admin.layouts.app')

@section('title', 'Level Income')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Level Income</h1>
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
                    <th>Level</th>
                    <th>From ID</th>
                    <th>From Name</th>
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
                        <td>Level {{ $list->level }}</td>
                        <td>{{ $list->level_id ?? 'N/A' }}</td>
                        <td>{{ $list->name }}</td>
                        <td>$ {{ (float)$list->amount == round((float)$list->amount, 2) ? number_format((float)$list->amount, 2) : rtrim(rtrim(number_format((float)$list->amount, 5), '0'), '.') }}</td>
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
                        <td colspan="9" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
