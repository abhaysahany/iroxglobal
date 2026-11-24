@extends('admin.layouts.app')

@section('title', 'Withdrawal Settings')

@section('content')
    <style>
        /* Top Overview Stat Cards */
        .ws-overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .ws-stat-card {
            background: #ffffff;
            border: 1px solid #e9edf5;
            border-radius: 12px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .ws-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        }

        .ws-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .ws-icon-primary {
            background: #f0ecfb;
            color: #845adf;
        }

        .ws-icon-success {
            background: #e6f7ee;
            color: #16a34a;
        }

        .ws-icon-warning {
            background: #fef6e7;
            color: #d97706;
        }

        .ws-icon-info {
            background: #e0f2fe;
            color: #0284c7;
        }

        .ws-stat-info {
            min-width: 0;
            flex: 1;
        }

        .ws-stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #727586;
            margin: 0 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ws-stat-value {
            font-size: 22px;
            font-weight: 800;
            color: #2b3347;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .ws-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            width: fit-content;
        }

        .ws-status-enabled {
            background: #e6f7ee;
            color: #16a34a;
        }

        .ws-status-disabled {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Modern Full-Width (col-12) Card */
        .ws-card {
            width: 100%;
            background: #ffffff;
            border: 1px solid #e9edf5;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .ws-card-header {
            padding: 20px 24px;
            background: #fafbfc;
            border-bottom: 1px solid #eef1f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .ws-card-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #2b3347;
            margin: 0 0 4px;
        }

        .ws-card-header p {
            font-size: 13px;
            color: #727586;
            margin: 0;
        }

        .ws-badge-pill {
            background: #f0ecfb;
            color: #845adf;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .ws-card-body {
            padding: 26px 24px;
        }

        /* 2-Column Responsive Form Grid */
        .ws-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px 26px;
        }

        .ws-col-full {
            grid-column: 1 / -1;
        }

        /* Form Group & Label */
        .ws-field-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .ws-field-label {
            font-size: 13.5px;
            font-weight: 700;
            color: #2b3347;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ws-field-label i {
            color: #845adf;
            font-size: 13px;
        }

        .ws-field-hint {
            font-size: 12px;
            color: #727586;
            margin: 2px 0 0;
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ws-field-hint i {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Premium Form Input Group */
        .ws-input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .ws-input-group:hover {
            border-color: #cbd5e1;
        }

        .ws-input-group:focus-within {
            border-color: #845adf;
            box-shadow: 0 0 0 4px rgba(132, 90, 223, 0.12);
        }

        .ws-input-addon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            min-height: 46px;
            padding: 0 14px;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
            font-weight: 700;
            user-select: none;
            transition: all 0.2s ease;
        }

        .ws-input-group:focus-within .ws-input-addon {
            border-color: #845adf;
            background: #f5f3ff;
            color: #845adf;
        }

        .ws-input {
            flex: 1;
            width: 100%;
            min-height: 46px;
            padding: 10px 14px !important;
            border: 0 !important;
            background: transparent !important;
            color: #1e293b;
            font-size: 14.5px;
            font-weight: 600;
            outline: none !important;
            box-shadow: none !important;
        }

        .ws-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Styled Select Box */
        .ws-select {
            flex: 1;
            width: 100%;
            min-height: 46px;
            padding: 10px 14px !important;
            border: 0 !important;
            background: transparent !important;
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            outline: none !important;
            box-shadow: none !important;
            cursor: pointer;
        }

        /* Styled Textarea */
        .ws-textarea-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .ws-textarea-wrap:hover {
            border-color: #cbd5e1;
        }

        .ws-textarea-wrap:focus-within {
            border-color: #845adf;
            box-shadow: 0 0 0 4px rgba(132, 90, 223, 0.12);
        }

        .ws-textarea {
            width: 100%;
            min-height: 80px;
            padding: 12px 14px !important;
            border: 0 !important;
            background: transparent !important;
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.5;
            outline: none !important;
            box-shadow: none !important;
            resize: vertical;
        }

        .ws-textarea::placeholder {
            color: #94a3b8;
        }

        /* Form Footer Actions */
        .ws-form-footer {
            margin-top: 26px;
            padding-top: 18px;
            border-top: 1px solid #eef1f6;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .ws-submit-btn {
            min-height: 46px;
            padding: 0 28px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 10px;
            background: #845adf;
            color: #ffffff;
            border: 0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(132, 90, 223, 0.32);
            transition: all 0.2s ease;
        }

        .ws-submit-btn:hover {
            background: #734bc9;
            box-shadow: 0 6px 18px rgba(132, 90, 223, 0.42);
            transform: translateY(-1px);
        }

        .ws-submit-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 991px) {
            .ws-form-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }
        }

        @media (max-width: 768px) {
            .ws-overview-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .ws-card-header,
            .ws-card-body {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .ws-overview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Withdrawal Settings</h1>
        </div>
        <div class="breadcrumb">
            <span>Home</span>
            <span>/</span>
            <span>Withdrawals</span>
            <span>/</span>
            <strong>Settings</strong>
        </div>
    </div>

    <!-- QUICK OVERVIEW METRICS -->
    <div class="ws-overview-grid">
        <div class="ws-stat-card">
            <div class="ws-stat-icon ws-icon-primary">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </div>
            <div class="ws-stat-info">
                <p class="ws-stat-label">Min Withdrawal</p>
                <h3 class="ws-stat-value">$ {{ number_format((float) ($setting->min_withdrawal ?? 10), 2) }}</h3>
            </div>
        </div>

        <div class="ws-stat-card">
            <div class="ws-stat-icon ws-icon-info">
                <i class="fa-solid fa-arrow-up-wide-short"></i>
            </div>
            <div class="ws-stat-info">
                <p class="ws-stat-label">Max Withdrawal</p>
                <h3 class="ws-stat-value">$ {{ number_format((float) ($setting->max_withdrawal ?? 5000), 2) }}</h3>
            </div>
        </div>

        <div class="ws-stat-card">
            <div class="ws-stat-icon ws-icon-warning">
                <i class="fa-solid fa-percent"></i>
            </div>
            <div class="ws-stat-info">
                <p class="ws-stat-label">Deduction Fee</p>
                <h3 class="ws-stat-value">{{ number_format((float) ($setting->withdrawal_fee_percent ?? 5), 2) }} %</h3>
            </div>
        </div>

        <div class="ws-stat-card">
            <div class="ws-stat-icon ws-icon-success">
                <i class="fa-solid fa-power-off"></i>
            </div>
            <div class="ws-stat-info">
                <p class="ws-stat-label">Gateway Status</p>
                @if (($setting->withdrawal_status ?? 'enabled') === 'enabled')
                    <span class="ws-status-badge ws-status-enabled">
                        <i class="fa-solid fa-circle" style="font-size: 6px;"></i> Active
                    </span>
                @else
                    <span class="ws-status-badge ws-status-disabled">
                        <i class="fa-solid fa-circle" style="font-size: 6px;"></i> Paused
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- FULL WIDTH COL-12 SETTINGS CARD -->
    <div class="row">
        <div class="col-12">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div>
                        <p class="page-kicker" style="color: #845adf; font-size: 11.5px; margin-bottom: 2px;">WITHDRAWAL CONFIGURATION</p>
                        <h2>Withdrawal System Settings</h2>
                        <p>Configure withdrawal minimum and maximum limits, percentage deductions, and live gateway availability.</p>
                    </div>
                    <span class="ws-badge-pill">
                        <i class="fa-solid fa-sliders"></i> System Config
                    </span>
                </div>

                <div class="ws-card-body">
                    @if (session('success'))
                        <div class="alert alert-success" style="margin-bottom: 22px;">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom: 22px;">
                            <strong>Please check the errors below:</strong>
                            <ul style="margin: 6px 0 0 18px; padding: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.withdrawals.settings.update') }}" method="POST">
                        @csrf

                        <div class="ws-form-grid">
                            <!-- MIN WITHDRAWAL -->
                            <div class="ws-field-group">
                                <label for="min_withdrawal" class="ws-field-label">
                                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                                    <span>Minimum Withdrawal ($)</span>
                                </label>
                                <div class="ws-input-group">
                                    <span class="ws-input-addon"><i class="fa-solid fa-dollar-sign"></i></span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="min_withdrawal"
                                        name="min_withdrawal"
                                        class="ws-input"
                                        value="{{ old('min_withdrawal', $setting->min_withdrawal ?? 10) }}"
                                        placeholder="10.00"
                                        required
                                    >
                                </div>
                                <span class="ws-field-hint">
                                    <i class="fa-regular fa-circle-info"></i>
                                    Minimum amount required to request a withdrawal.
                                </span>
                                @error('min_withdrawal')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- MAX WITHDRAWAL -->
                            <div class="ws-field-group">
                                <label for="max_withdrawal" class="ws-field-label">
                                    <i class="fa-solid fa-arrow-up-wide-short"></i>
                                    <span>Maximum Withdrawal ($)</span>
                                </label>
                                <div class="ws-input-group">
                                    <span class="ws-input-addon"><i class="fa-solid fa-dollar-sign"></i></span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="max_withdrawal"
                                        name="max_withdrawal"
                                        class="ws-input"
                                        value="{{ old('max_withdrawal', $setting->max_withdrawal ?? 5000) }}"
                                        placeholder="5000.00"
                                        required
                                    >
                                </div>
                                <span class="ws-field-hint">
                                    <i class="fa-regular fa-circle-info"></i>
                                    Maximum limit allowed in a single withdrawal request.
                                </span>
                                @error('max_withdrawal')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- WITHDRAWAL FEE / DEDUCTION -->
                            <div class="ws-field-group">
                                <label for="withdrawal_fee_percent" class="ws-field-label">
                                    <i class="fa-solid fa-percent"></i>
                                    <span>Withdrawal Deduction Fee (%)</span>
                                </label>
                                <div class="ws-input-group">
                                    <span class="ws-input-addon"><i class="fa-solid fa-percent"></i></span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        id="withdrawal_fee_percent"
                                        name="withdrawal_fee_percent"
                                        class="ws-input"
                                        value="{{ old('withdrawal_fee_percent', $setting->withdrawal_fee_percent ?? 5) }}"
                                        placeholder="5.00"
                                        required
                                    >
                                </div>
                                <span class="ws-field-hint">
                                    <i class="fa-regular fa-circle-info"></i>
                                    Percentage fee deducted from each withdrawal amount.
                                </span>
                                @error('withdrawal_fee_percent')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- DAILY WITHDRAWAL LIMIT -->
                            <div class="ws-field-group">
                                <label for="daily_max_withdrawal" class="ws-field-label">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    <span>Daily Max Withdrawal Limit ($)</span>
                                </label>
                                <div class="ws-input-group">
                                    <span class="ws-input-addon"><i class="fa-solid fa-dollar-sign"></i></span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="daily_max_withdrawal"
                                        name="daily_max_withdrawal"
                                        class="ws-input"
                                        value="{{ old('daily_max_withdrawal', $setting->daily_max_withdrawal ?? 10000) }}"
                                        placeholder="10000.00"
                                    >
                                </div>
                                <span class="ws-field-hint">
                                    <i class="fa-regular fa-circle-info"></i>
                                    Maximum daily withdrawal per member (enter 0 for unlimited).
                                </span>
                                @error('daily_max_withdrawal')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- GATEWAY STATUS -->
                            <div class="ws-field-group ws-col-full">
                                <label for="withdrawal_status" class="ws-field-label">
                                    <i class="fa-solid fa-power-off"></i>
                                    <span>Withdrawal Gateway Status</span>
                                </label>
                                <div class="ws-input-group">
                                    <span class="ws-input-addon"><i class="fa-solid fa-shield-halved"></i></span>
                                    <select id="withdrawal_status" name="withdrawal_status" class="ws-select">
                                        <option value="enabled" @selected(old('withdrawal_status', $setting->withdrawal_status ?? 'enabled') === 'enabled')>
                                            &#x2705; Enabled - Withdrawals Active (Members can submit requests)
                                        </option>
                                        <option value="disabled" @selected(old('withdrawal_status', $setting->withdrawal_status ?? 'enabled') === 'disabled')>
                                            &#x26D4; Disabled - Withdrawals Paused (Maintenance mode)
                                        </option>
                                    </select>
                                </div>
                                <span class="ws-field-hint">
                                    <i class="fa-regular fa-circle-info"></i>
                                    Turn OFF to temporarily stop new withdrawal requests.
                                </span>
                                @error('withdrawal_status')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- WITHDRAWAL NOTICE -->
                            <div class="ws-field-group ws-col-full">
                                <label for="withdrawal_notice" class="ws-field-label">
                                    <i class="fa-regular fa-message"></i>
                                    <span>Withdrawal Notice / Member Instructions</span>
                                </label>
                                <div class="ws-textarea-wrap">
                                    <textarea
                                        id="withdrawal_notice"
                                        name="withdrawal_notice"
                                        rows="3"
                                        class="ws-textarea"
                                        placeholder="e.g. Withdrawal requests are processed within 24 to 48 hours."
                                    >{{ old('withdrawal_notice', $setting->withdrawal_notice ?? 'Withdrawals are processed within 24 to 48 hours.') }}</textarea>
                                </div>
                                <span class="ws-field-hint">
                                    <i class="fa-regular fa-circle-info"></i>
                                    This note will be shown to members on their wallet withdrawal form.
                                </span>
                                @error('withdrawal_notice')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- SUBMIT ACTION -->
                        <div class="ws-form-footer">
                            <button type="submit" class="ws-submit-btn">
                                <i class="fa-solid fa-check"></i>
                                <span>Save Withdrawal Settings</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
