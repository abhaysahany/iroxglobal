@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>{{ $title }}</h1>
        </div>
    </div>

    @if ($type === 'settings')
        <section class="panel settings-panel deposit-settings-panel">
            <div class="panel-heading">
                <div>
                    <p class="section-label">Gateway</p>
                    <h2>Deposit Gateway Configuration</h2>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please check the form errors and try again.
                </div>
            @endif

            <form action="{{ route('admin.deposits.settings.update') }}" method="POST" class="settings-form">
                @csrf

                <div class="form-group">
                    <label for="company_bsc_wallet_address">Company BSC Wallet Address</label>
                    <input
                        type="text"
                        id="company_bsc_wallet_address"
                        name="company_bsc_wallet_address"
                        value="{{ old('company_bsc_wallet_address', $setting->company_bsc_wallet_address ?? '') }}"
                        placeholder="0x0000000000000000000000000000000000000000"
                    >
                    @error('company_bsc_wallet_address')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="minimum_deposit_bnb">Minimum Deposit (BNB)</label>
                        <div class="deposit-input-group">
                            <span>BNB</span>
                            <input
                                type="number"
                                step="0.00000001"
                                min="0"
                                id="minimum_deposit_bnb"
                                name="minimum_deposit_bnb"
                                value="{{ old('minimum_deposit_bnb', $setting->minimum_deposit_bnb ?? '') }}"
                                placeholder="10.00000000"
                            >
                        </div>
                        @error('minimum_deposit_bnb')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="maximum_deposit_bnb">Maximum Deposit (BNB)</label>
                        <div class="deposit-input-group">
                            <span>BNB</span>
                            <input
                                type="number"
                                step="0.00000001"
                                min="0"
                                id="maximum_deposit_bnb"
                                name="maximum_deposit_bnb"
                                value="{{ old('maximum_deposit_bnb', $setting->maximum_deposit_bnb ?? '') }}"
                                placeholder="1000.00000000"
                            >
                        </div>
                        @error('maximum_deposit_bnb')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="deposit_gateway_state">Gateway State</label>
                    <select id="deposit_gateway_state" name="deposit_gateway_state">
                        <option value="enabled" @selected(old('deposit_gateway_state', $setting->deposit_gateway_state ?? 'enabled') === 'enabled')>
                            Enabled / Allow Deposits
                        </option>
                        <option value="disabled" @selected(old('deposit_gateway_state', $setting->deposit_gateway_state ?? 'enabled') === 'disabled')>
                            Disabled / Stop Deposits
                        </option>
                    </select>
                    @error('deposit_gateway_state')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full">Commit Configuration Changes</button>
                </div>
            </form>
        </section>
    @else
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
            @if ($type === 'reports')
                <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Total Deposit</th>
                        <th>Approved Deposit</th>
                        <th>Pending Deposit</th>
                        <th>Rejected Deposit</th>
                        <th>Last Deposit Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deposits as $deposit)
                        <tr>
                            <td>{{ $deposits->firstItem() + $loop->index }}</td>
                            <td>{{ $deposit->member?->member_id ?? '-' }}</td>
                            <td>{{ $deposit->member?->name ?? '-' }}</td>
                            <td>{{ number_format($deposit->total_deposit, 2) }}</td>
                            <td>{{ number_format($deposit->approved_deposit, 2) }}</td>
                            <td>{{ number_format($deposit->pending_deposit, 2) }}</td>
                            <td>{{ number_format($deposit->rejected_deposit, 2) }}</td>
                            <td>{{ $deposit->last_deposit_date ? \Carbon\Carbon::parse($deposit->last_deposit_date)->format('d M Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align: center; padding: 18px;">No deposit reports found.</td></tr>
                    @endforelse
                </tbody>
            @else
                <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Request Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deposits as $deposit)
                        <tr>
                            <td>{{ $deposits->firstItem() + $loop->index }}</td>
                            <td>{{ $deposit->member?->member_id ?? '-' }}</td>
                            <td>{{ $deposit->member?->name ?? '-' }}</td>
                            <td>{{ $deposit->member?->email ?? '-' }}</td>
                            <td>{{ number_format($deposit->amount, 2) }}</td>
                            <td>{{ $deposit->payment_mode }}</td>
                            <td>{{ $deposit->transaction_id ?? '-' }}</td>
                            <td><span class="status {{ $deposit->status === 'Approved' ? 'success' : ($deposit->status === 'Rejected' ? 'danger' : 'warning') }}">{{ $deposit->status }}</span></td>
                            <td>{{ $deposit->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" style="text-align: center; padding: 18px;">No deposits found.</td></tr>
                    @endforelse
                </tbody>
            @endif
            </table>
        </div>

        @if (method_exists($deposits, 'links'))
            {{ $deposits->links() }}
        @endif
    @endif
@endsection
