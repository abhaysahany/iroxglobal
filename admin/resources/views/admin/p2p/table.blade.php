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
                @if ($type === 'member-wallets')
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Wallet Balance</th>
                        <th>Total Credit</th>
                        <th>Total Debit</th>
                        <th>Wallet Status</th>
                        <th>Updated Date</th>
                    </tr>
                @elseif ($type === 'wallet-ledger')
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Transaction Type</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                        <th>Description</th>
                        <th>Transaction Date</th>
                    </tr>
                @elseif ($type === 'wallet-adjustments')
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Adjustment Type</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Adjusted By</th>
                        <th>Status</th>
                        <th>Created Date</th>
                    </tr>
                @elseif ($type === 'wallet-reports')
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Opening Balance</th>
                        <th>Total Credit</th>
                        <th>Total Debit</th>
                        <th>Closing Balance</th>
                        <th>Last Transaction Date</th>
                    </tr>
                @else
                    <tr>
                        <th>Sr No.</th>
                        <th>Admin/User</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Action</th>
                        <th>Old Balance</th>
                        <th>New Balance</th>
                        <th>IP Address</th>
                        <th>Created Date</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    @php
                        $serial = (($rows->currentPage() - 1) * $rows->perPage()) + $loop->iteration;
                    @endphp

                    @if ($type === 'member-wallets')
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->member_email ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->wallet_balance ?? null) }}</td>
                            <td>{{ $formatAmount($row->total_credit ?? null) }}</td>
                            <td>{{ $formatAmount($row->total_debit ?? null) }}</td>
                            <td>{{ $row->wallet_status ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->updated_date ?? null) }}</td>
                        </tr>
                    @elseif ($type === 'wallet-ledger')
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->transaction_type ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->credit ?? null) }}</td>
                            <td>{{ $formatAmount($row->debit ?? null) }}</td>
                            <td>{{ $formatAmount($row->balance ?? null) }}</td>
                            <td>{{ $row->description ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->transaction_date ?? null) }}</td>
                        </tr>
                    @elseif ($type === 'wallet-adjustments')
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->adjustment_type ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->amount ?? null) }}</td>
                            <td>{{ $row->reason ?? 'N/A' }}</td>
                            <td>{{ $row->adjusted_by ?? 'N/A' }}</td>
                            <td>{{ $row->status ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->created_at ?? null) }}</td>
                        </tr>
                    @elseif ($type === 'wallet-reports')
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->opening_balance ?? null) }}</td>
                            <td>{{ $formatAmount($row->total_credit ?? null) }}</td>
                            <td>{{ $formatAmount($row->total_debit ?? null) }}</td>
                            <td>{{ $formatAmount($row->closing_balance ?? null) }}</td>
                            <td>{{ $formatDate($row->last_transaction_date ?? null) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $row->admin_user ?? 'N/A' }}</td>
                            <td>{{ $row->member_code ?? 'N/A' }}</td>
                            <td>{{ $row->member_name ?? 'N/A' }}</td>
                            <td>{{ $row->action ?? 'N/A' }}</td>
                            <td>{{ $formatAmount($row->old_balance ?? null) }}</td>
                            <td>{{ $formatAmount($row->new_balance ?? null) }}</td>
                            <td>{{ $row->ip_address ?? 'N/A' }}</td>
                            <td>{{ $formatDate($row->created_at ?? null) }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="{{ $type === 'wallet-reports' ? 8 : 9 }}" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $rows->links() }}
@endsection
