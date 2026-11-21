@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    @php
        $formatDate = function ($value) {
            if (! $value) {
                return 'N/A';
            }

            try {
                return $value instanceof \Carbon\CarbonInterface
                    ? $value->format('d M Y')
                    : \Illuminate\Support\Carbon::parse($value)->format('d M Y');
            } catch (\Throwable $e) {
                return 'N/A';
            }
        };

        $formatAmount = function ($value) {
            return is_numeric($value) ? number_format((float) $value, 2) : 'N/A';
        };
    @endphp

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>{{ $title }}</h1>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table table-bordered table-striped admin-datatable">
            <thead>
                @if ($type === 'activations')
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Package Name</th>
                        <th>Package Amount</th>
                        <th>Activation Status</th>
                        <th>Activated At</th>
                        <th>Created Date</th>
                    </tr>
                @elseif ($type === 'upgrades')
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Old Package</th>
                        <th>New Package</th>
                        <th>Upgrade Amount</th>
                        <th>Upgrade Status</th>
                        <th>Upgrade Date</th>
                    </tr>
                @else
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Package Name</th>
                        <th>Package Amount</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Created Date</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    @php
                        $serial = (($rows->currentPage() - 1) * $rows->perPage()) + $loop->iteration;
                    @endphp

                    @if ($type === 'activations')
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->member_email ?? 'N/A' }}</td>
                            <td>{{ $row->package_name ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->package_amount ?? null) }}</td>
                            <td>{{ $row->status ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->activated_at ?? null) }}</td>
                            <td>{{ $formatDate($row->created_at ?? null) }}</td>
                        </tr>
                    @elseif ($type === 'upgrades')
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->member_email ?? 'N/A' }}</td>
                            <td>{{ $row->old_package ?? 'N/A' }}</td>
                            <td>{{ $row->new_package ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->upgrade_amount ?? $row->package_amount ?? null) }}</td>
                            <td>{{ $row->upgrade_status ?? $row->status ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->upgraded_at ?? $row->activated_at ?? $row->created_at ?? null) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->member_email ?? 'N/A' }}</td>
                            <td>{{ $row->package_name ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->package_amount ?? null) }}</td>
                            <td>{{ $row->status ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->start_date ?? $row->activated_at ?? null) }}</td>
                            <td>{{ $formatDate($row->end_date ?? null) }}</td>
                            <td>{{ $formatDate($row->created_at ?? null) }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="{{ $type === 'package-history' ? 10 : 9 }}" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $rows->links() }}
@endsection
