@extends('admin.layouts.app')
@section('title', $title)
@section('content')
    @php
        $formatDate = function ($value) {
            if (!$value) {
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

        $maskPrivateKey = function ($value) {
            if (!$value) {
                return 'N/A';
            }

            return '************' . substr((string) $value, -4);
        };
    @endphp

    <style>
        .withdrawal-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .withdrawal-action {
            min-height: 34px;
            padding: 6px 12px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 12px;
            border: 0;
            cursor: pointer;
        }

        .withdrawal-accept {
            background: #16a34a;
        }

        .withdrawal-cancel {
            background: #e11d48;
        }

        .withdrawal-status-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 5px 12px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
        }

        .withdrawal-dialog {
            width: min(92vw, 520px);
            padding: 0;
            border: 0;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
        }

        .withdrawal-dialog::backdrop {
            background: rgba(15, 23, 42, 0.55);
        }

        .withdrawal-dialog-content {
            padding: 22px;
        }

        .withdrawal-dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .withdrawal-dialog-header h3 {
            margin: 0;
            font-size: 18px;
        }
    </style>

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>{{ $title }}</h1>
        </div>
    </div>


    <section class="panel" style="margin-top: 24px;">
        <div class="panel-heading">
            <h2>{{ $title }} List</h2>
        </div>
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    @if ($type === 'requests')
                        <tr>
                            <th>Sr No.</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Email</th>
                            <th>Gross Amount</th>
                            <th>Charges</th>
                            <th>Net Amount</th>
                            <th>Payment Method</th>
                            <th>Account Details</th>
                            <th>Status</th>
                            <th>Request Date</th>
                            <th>Accept</th>
                            <th>Cancel</th>
                        </tr>
                    @elseif ($type === 'approved')
                        <tr>
                            <th>Sr No.</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Email</th>
                            <th>Gross Amount</th>
                            <th>Charges</th>
                            <th>Net Amount</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                            <th>Approved Date</th>
                            <th>Status</th>
                        </tr>
                    @elseif ($type === 'cancelled')
                        <tr>
                            <th>Sr No.</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Email</th>
                            <th>Gross Amount</th>
                            <th>Charges</th>
                            <th>Net Amount</th>
                            <th>Payment Method</th>
                            <th>Cancel Reason/Admin Remark</th>
                            <th>Cancelled Date</th>
                            <th>Status</th>
                        </tr>
                    @elseif ($type === 'reports')
                        <tr>
                            <th>Sr No.</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Total Withdrawal</th>
                            <th>Approved Withdrawal</th>
                            <th>Pending Withdrawal</th>
                            <th>Cancelled Withdrawal</th>
                            <th>Last Withdrawal Date</th>
                        </tr>
                    @else
                        <tr>
                            <th>Sr No.</th>
                            <th>Member ID</th>
                            <th>Member Name</th>
                            <th>Wallet Address</th>
                            <th>Network</th>
                            <th>Key Status</th>
                            <th>Updated Date</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php
                            $serial = method_exists($rows, 'currentPage')
                                ? ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration
                                : $loop->iteration;
                        @endphp

                        @if ($type === 'requests')
                            <tr>
                                <td style="vertical-align: middle;">{{ $serial }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_code ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_name ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_email ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->gross_amount ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->service_charge ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->net_amount ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->payment_method ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->account_details ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->status ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $formatDate($row->request_date ?? null) }}</td>
                                <td style="vertical-align: middle;">
                                    <form action="{{ route('admin.withdrawal.quick-accept') }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="wid" value="{{ $row->id }}">
                                        <button type="submit" class="withdrawal-action withdrawal-accept">Accept</button>
                                    </form>
                                </td>
                                <td style="vertical-align: middle;">
                                    <button type="button" class="withdrawal-action withdrawal-cancel"
                                        onclick="document.getElementById('cancel-withdrawal-{{ $row->id }}').showModal()">Cancel</button>

                                    <dialog id="cancel-withdrawal-{{ $row->id }}" class="withdrawal-dialog">
                                        <div class="withdrawal-dialog-content">
                                            <div class="withdrawal-dialog-header">
                                                <h3>Cancel Withdrawal Request</h3>
                                                <button type="button" class="btn btn-outline"
                                                    onclick="this.closest('dialog').close()">Close</button>
                                            </div>

                                            <form action="{{ route('admin.withdrawal.cancel') }}" method="POST"
                                                class="settings-form" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="rid" value="{{ $row->id }}">

                                                <div class="form-group">
                                                    <label for="reason-{{ $row->id }}">Reason</label>
                                                    <textarea name="reason" id="reason-{{ $row->id }}" rows="3" required></textarea>
                                                </div>

                                                <div class="form-actions">
                                                    <button type="submit"
                                                        class="withdrawal-action withdrawal-cancel">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>
                                </td>
                            </tr>
                        @elseif ($type === 'approved')
                            <tr>
                                <td style="vertical-align: middle;">{{ $serial }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_code ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_name ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_email ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->gross_amount ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->service_charge ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->net_amount ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->payment_method ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->transaction_id ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $formatDate($row->updated_date ?? null) }}</td>
                                <td style="vertical-align: middle;"><span class="withdrawal-status-btn withdrawal-accept">{{ $row->status ?? 'N/A' }}</span></td>
                            </tr>
                        @elseif ($type === 'cancelled')
                            <tr>
                                <td style="vertical-align: middle;">{{ $serial }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_code ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_name ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_email ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->gross_amount ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->service_charge ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->net_amount ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->payment_method ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->admin_remark ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $formatDate($row->updated_date ?? null) }}</td>
                                <td style="vertical-align: middle;"><span class="withdrawal-status-btn withdrawal-cancel">{{ $row->status ?? 'N/A' }}</span></td>
                            </tr>
                        @elseif ($type === 'reports')
                            <tr>
                                <td style="vertical-align: middle;">{{ $serial }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_code ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_name ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->total_withdrawal ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->approved_withdrawal ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->pending_withdrawal ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $row->cancelled_withdrawal ?? null }}</td>
                                <td style="vertical-align: middle;">{{ $formatDate($row->last_withdrawal_date ?? null) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td style="vertical-align: middle;">{{ $serial }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_code ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->member_name ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->wallet_address ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->network ?? 'N/A' }}</td>
                                <td style="vertical-align: middle;">{{ $row->key_status ?? $maskPrivateKey($row->private_key_value ?? null) }}</td>
                                <td style="vertical-align: middle;">{{ $formatDate($row->updated_date ?? null) }}</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ $type === 'reports' ? 8 : ($type === 'private-keys' ? 7 : ($type === 'requests' ? 13 : 11)) }}"
                                style="text-align: center; padding: 18px; vertical-align: middle;">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($rows, 'links'))
            <div style="padding: 0 22px 22px;">
                {{ $rows->links() }}
            </div>
        @endif
    </section>
@endsection
