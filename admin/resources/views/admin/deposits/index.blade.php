@extends('admin.layouts.app')

@section('title', 'Deposit Details')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Deposit Details</h1>
        </div>
    </div>

    <section class="panel settings-panel">
        <div class="panel-heading">
            <h2>Deposit Details Form</h2>
        </div>

        <form action="{{ route('admin.deposits.payment-details.store') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="wallet_address">Wallet Address</label>
                    <input
                        type="text"
                        id="wallet_address"
                        name="wallet_address"
                        value="{{ old('wallet_address') }}"
                        placeholder="Enter Wallet Address"
                        required
                    >
                    @error('wallet_address')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="qr_code">QR Code</label>
                    <input
                        type="file"
                        id="qr_code"
                        name="qr_code"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        required
                    >
                    @error('qr_code')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions" style="justify-content: center;">
                <button type="submit" class="btn btn-primary">Submit Deposit Details</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="panel-heading">
            <h2>Deposit Details Table</h2>
        </div>

        <div class="table-wrap table-responsive">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>QR Image</th>
                        <th>Wallet Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentDetails as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($detail->qrcode)
                                    @php
                                        $qrPath = str_contains($detail->qrcode, '/')
                                            ? $detail->qrcode
                                            : 'uploads/payment_qr/'.$detail->qrcode;
                                    @endphp
                                    @if (is_file(public_path($qrPath)))
                                        <img src="{{ asset($qrPath) }}" alt="QR Code" width="60" height="60" style="object-fit: contain;">
                                    @else
                                        --
                                    @endif
                                @else
                                    --
                                @endif
                            </td>
                            <td>{{ $detail->address ?? '--' }}</td>
                            <td>
                                <span class="status {{ strtolower($detail->status) === 'live' ? 'success' : 'warning' }}">
                                    {{ strtolower($detail->status) === 'live' ? 'Live' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No payment details found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
