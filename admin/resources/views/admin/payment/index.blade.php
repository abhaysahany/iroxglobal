@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <style>
        .payment-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .payment-heading-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 50px;
            width: 50px;
            height: 50px;
            border: 1px solid #e9edf5;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 5px 14px rgba(15, 23, 42, 0.08);
            color: var(--primary);
            font-size: 20px;
        }

        .payment-heading h1 {
            margin-bottom: 4px;
        }

        .payment-heading p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .payment-card {
            padding: 0;
            overflow: hidden;
        }

        .payment-card .panel-heading {
            margin: 0;
            padding: 18px 22px;
            border-bottom: 1px solid #eef1f6;
        }

        .payment-card .table-wrap {
            padding: 20px 22px 22px;
        }

        .payment-card thead th {
            background: #f4f6f9;
        }

        .payment-action {
            min-height: 34px;
            padding: 6px 12px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 12px;
        }

        .payment-accept {
            background: #16a34a;
        }

        .payment-reject {
            background: #e11d48;
        }

        .payment-action:hover {
            color: #ffffff;
            filter: brightness(0.92);
        }

        .payment-dialog {
            width: min(92vw, 520px);
            padding: 0;
            border: 0;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
        }

        .payment-dialog::backdrop {
            background: rgba(15, 23, 42, 0.55);
        }

        .payment-dialog-content {
            padding: 22px;
        }

        .payment-dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .payment-dialog-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .payment-dialog-close {
            min-height: 34px;
            padding: 5px 10px;
        }

        @media (max-width: 760px) {
            .payment-page-header {
                align-items: flex-start;
            }
        }
    </style>

    <div class="page-header payment-page-header">
        <div class="payment-heading">
            <span class="payment-heading-icon"><i class="fa-solid fa-credit-card"></i></span>
            <div>
                <h1>{{ $title }}</h1>
                <p>{{ $subtitle }}</p>
            </div>
        </div>

        <div class="breadcrumb">
            <span><i class="fa-solid fa-house"></i></span>
            <span>/</span>
            <span>Admin</span>
            <span>/</span>
            <strong>{{ $title }}</strong>
        </div>
    </div>

    @if ($page === 'history')
        <section class="panel" style="margin-bottom: 20px;">
            <form action="{{ route('admin.payment.history') }}" method="GET" class="settings-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Statuses</option>
                            @foreach (['Pending', 'Requested', 'Approved', 'Rejected', 'Cancelled'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="memberid">Memberid</label>
                        <input type="text" name="memberid" id="memberid" value="{{ request('memberid') }}" placeholder="Enter Memberid">
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}">
                    </div>
                </div>
                <div class="form-actions" style="gap: 10px;">
                    <a href="{{ route('admin.payment.history') }}" class="btn btn-outline">Reset</a>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </section>
    @endif

    <section class="panel payment-card">
        <div class="panel-heading">
            <h2>{{ $title }}</h2>
        </div>

        <div class="table-wrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Request Date</th>
                        <th>Memberid</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        @if ($page !== 'history')<th>Wallet Address / Account Details</th>@endif
                        <th>Status</th>
                        @if ($page === 'new')
                            <th>Accept</th>
                            <th>Reject</th>
                        @elseif ($page === 'cancelled')
                            <th>Cancelled Date</th>
                        @else
                            <th>Approved/Rejected Date</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawals->firstItem() + $loop->index }}</td>
                            <td>{{ $withdrawal->request_date?->format('d M Y, h:i A') ?? '-' }}</td>
                            <td>{{ $withdrawal->memberid }}</td>
                            <td>{{ $withdrawal->member?->name ?? $withdrawal->name ?? '-' }}</td>
                            <td>{{ number_format((float) $withdrawal->gross_amount, 2) }}</td>
                            <td>{{ $withdrawal->type }}</td>
                            @if ($page !== 'history')<td>{{ $withdrawal->wallet_address ?? '-' }}</td>@endif
                            <td>
                                <span class="status {{ $withdrawal->status === 'Approved' ? 'success' : (in_array($withdrawal->status, ['Rejected', 'Cancelled', 'Canceled'], true) ? 'danger' : 'warning') }}">
                                    {{ $withdrawal->status }}
                                </span>
                            </td>
                            @if ($page === 'new')
                                <td>
                                    <button type="button" class="btn payment-action payment-accept" onclick="document.getElementById('accept-payment-{{ $withdrawal->id }}').showModal()">Accept</button>

                                    <dialog id="accept-payment-{{ $withdrawal->id }}" class="payment-dialog">
                                        <div class="payment-dialog-content">
                                            <div class="payment-dialog-header">
                                                <h3>Accept Withdrawal Request</h3>
                                                <button type="button" class="btn btn-outline payment-dialog-close" onclick="this.closest('dialog').close()">Close</button>
                                            </div>
                                            <form action="{{ route('admin.withdrawal.accept') }}" method="POST" enctype="multipart/form-data" class="settings-form">
                                                @csrf
                                                <input type="hidden" name="wid" value="{{ $withdrawal->id }}">
                                                <div class="form-group">
                                                    <label for="utr-{{ $withdrawal->id }}">UTR</label>
                                                    <input type="text" name="utr" id="utr-{{ $withdrawal->id }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="otherdetail-{{ $withdrawal->id }}">Other Detail</label>
                                                    <textarea name="otherdetail" id="otherdetail-{{ $withdrawal->id }}" rows="3"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image-{{ $withdrawal->id }}">Payment Proof</label>
                                                    <input type="file" name="image" id="image-{{ $withdrawal->id }}" accept=".png,.jpg,.jpeg,image/png,image/jpeg" required>
                                                </div>
                                                <div class="form-actions">
                                                    <button type="submit" class="btn payment-action payment-accept">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>
                                </td>
                                <td>
                                    <button type="button" class="btn payment-action payment-reject" onclick="document.getElementById('cancel-payment-{{ $withdrawal->id }}').showModal()">Cancel</button>

                                    <dialog id="cancel-payment-{{ $withdrawal->id }}" class="payment-dialog">
                                        <div class="payment-dialog-content">
                                            <div class="payment-dialog-header">
                                                <h3>Cancel Withdrawal Request</h3>
                                                <button type="button" class="btn btn-outline payment-dialog-close" onclick="this.closest('dialog').close()">Close</button>
                                            </div>
                                            <form action="{{ route('admin.withdrawal.cancel') }}" method="POST" class="settings-form">
                                                @csrf
                                                <input type="hidden" name="rid" value="{{ $withdrawal->id }}">
                                                <div class="form-group">
                                                    <label for="reason-{{ $withdrawal->id }}">Reason</label>
                                                    <textarea name="reason" id="reason-{{ $withdrawal->id }}" rows="3" required></textarea>
                                                </div>
                                                <div class="form-actions">
                                                    <button type="submit" class="btn payment-action payment-reject">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>
                                </td>
                            @else
                                <td>{{ $withdrawal->payment_date?->format('d M Y, h:i A') ?? $withdrawal->updated_at?->format('d M Y, h:i A') ?? '-' }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $page === 'new' ? 10 : ($page === 'cancelled' ? 9 : 8) }}">No payment records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 0 22px 22px;">{{ $withdrawals->links() }}</div>
    </section>
@endsection
