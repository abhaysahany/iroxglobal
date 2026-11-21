@extends('admin.layouts.app')

@section('title', 'New Funds Requests')

@section('content')
    <style>
        .new-funds-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .new-funds-heading-icon {
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
            color: #0d6efd;
            font-size: 20px;
        }

        .new-funds-heading h1 {
            margin-bottom: 4px;
        }

        .new-funds-heading p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .new-funds-card {
            padding: 0;
            overflow: hidden;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .new-funds-card .panel-heading {
            margin: 0;
            padding: 18px 22px;
            border-bottom: 1px solid #eef1f6;
        }

        .new-funds-card .table-wrap {
            padding: 20px 22px 22px;
        }

        .new-funds-card thead th {
            background: #f4f6f9;
        }

        .request-action {
            min-height: 34px;
            padding: 6px 12px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 12px;
        }

        .request-view {
            background: #f97316;
        }

        .request-accept {
            background: #16a34a;
        }

        .request-reject {
            background: #e11d48;
        }

        .request-action:hover {
            color: #ffffff;
            filter: brightness(0.92);
        }

        .request-actions {
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        @media (max-width: 760px) {
            .new-funds-page-header {
                align-items: flex-start;
            }
        }
    </style>

    <div class="page-header new-funds-page-header">
        <div class="new-funds-heading">
            <span class="new-funds-heading-icon"><i class="fa-solid fa-money-check-dollar"></i></span>
            <div>
                <h1>New Funds Requests</h1>
                <p>All New Funds Requests</p>
            </div>
        </div>

        <div class="breadcrumb">
            <span><i class="fa-solid fa-house"></i></span>
            <span>/</span>
            <span>Admin</span>
            <span>/</span>
            <strong>New Funds Requests</strong>
        </div>
    </div>

    <section class="panel new-funds-card">
        <div class="panel-heading">
            <h2>New Funds Requests</h2>
        </div>

        <div class="table-wrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Request Date</th>
                        <th>Memberid</th>
                        <th>Name</th>
                        <th>TxnId</th>
                        <th>Type</th>
                        <th>USDT Amount</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Accept</th>
                        <th>Reject</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $fundRequest)
                        <tr>
                            <td>{{ $requests->firstItem() + $loop->index }}</td>
                            <td>{{ $fundRequest->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                            <td>{{ $fundRequest->memberid }}</td>
                            <td>{{ $fundRequest->member?->name ?? '-' }}</td>
                            <td>{{ $fundRequest->txnid ?? '-' }}</td>
                            <td>{{ $fundRequest->type }}</td>
                            <td>{{ number_format((float) $fundRequest->amount, 2) }}</td>
                            <td><span class="status warning">{{ $fundRequest->status }}</span></td>
                            <td>
                                @if ($fundRequest->image)
                                    <a href="{{ asset(ltrim($fundRequest->image, '/')) }}" target="_blank" rel="noopener" class="btn request-action request-view">View</a>
                                @else
                                    <button type="button" class="btn request-action request-view" disabled>No Image</button>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.funds.requests.accept', $fundRequest->id) }}" method="POST" class="request-actions">
                                    @csrf
                                    <button type="submit" class="btn request-action request-accept" onclick="return confirm('Accept this fund request?')">Accept</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.funds.requests.reject', $fundRequest->id) }}" method="POST" class="request-actions">
                                    @csrf
                                    <button type="submit" class="btn request-action request-reject" onclick="return confirm('Reject this fund request?')">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" style="text-align: center; padding: 18px;">No new fund requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 0 22px 22px;">{{ $requests->links() }}</div>
    </section>
@endsection
