<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Withdrawal Approved</title>
    <style>
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 40px 0;
        }

        .main-table {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .header {
            background: #0d8a43;
            background: linear-gradient(135deg, #0d8a43 0%, #054a22 100%);
            padding: 45px 40px 40px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 800;
            margin: 15px 0 0;
            color: #ffffff;
        }

        .header p {
            font-size: 13px;
            margin: 6px 0 0;
            color: #a3e635;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .content {
            padding: 40px;
            color: #334155;
        }

        .status-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .status-icon {
            display: inline-block;
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #059669;
            font-size: 42px;
            font-weight: 800;
            line-height: 74px;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            background-color: #ecfdf5;
            color: #059669;
            font-size: 13px;
            font-weight: 800;
            border-radius: 50px;
            border: 1px solid #a7f3d0;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 18px 20px;
            margin: 24px 0;
            text-align: center;
        }

        .summary-label {
            color: #166534;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0 0 4px;
        }

        .summary-amount {
            color: #052e16;
            font-size: 28px;
            font-weight: 800;
            margin: 0;
        }

        .details-table {
            width: 100%;
            margin-top: 25px;
            background-color: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .details-table td {
            padding: 15px 20px;
            font-size: 14px;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-value {
            font-weight: 700;
            text-align: right;
            color: #0f172a;
            word-break: break-word;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin: 0;
        }

        .button-container {
            text-align: center;
            margin: 30px 0 15px;
        }

        .button {
            display: inline-block;
            padding: 14px 35px;
            background: #10b981;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .support-text {
            font-size: 13px;
            color: #64748b;
            margin-top: 30px;
            text-align: center;
            line-height: 1.5;
        }

        .footer {
            background-color: #f8fafc;
            padding: 35px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <center class="wrapper">
        <table class="main-table" role="presentation" cellpadding="0" cellspacing="0">
            <tr>
                <td class="header">
                    <img src="{{ $message->embed(public_path() . '/admin_assets/images/logo.png') }}" width="140"
                        alt="IroxFx Global Logo"
                        style="display: inline-block; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));">
                    <h1>Withdrawal Approved</h1>
                    <p>Secure Trading & Financial Services</p>
                </td>
            </tr>

            <tr>
                <td class="content">
                    <div class="status-section">
                        <span class="status-icon">✓</span>
                        <br>
                        <span class="status-badge">Approved</span>
                    </div>

                    <p style="font-weight: 700; font-size: 17px; color: #0f172a; margin-bottom: 12px;">
                        Hello {{ $name ?? 'Member' }},
                    </p>
                    <p>Your withdrawal request has been approved successfully. The approved withdrawal details are listed below.</p>

                    <div class="summary-box">
                        <p class="summary-label">Net Amount</p>
                        <p class="summary-amount">${{ number_format((float) ($net_amount ?? 0), 2) }}</p>
                    </div>

                    <table class="details-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="details-label">Member ID</td>
                            <td class="details-value">{{ $memberid ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Request ID</td>
                            <td class="details-value">{{ $request_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Gross Amount</td>
                            <td class="details-value">${{ number_format((float) ($gross_amount ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Service Charge</td>
                            <td class="details-value">${{ number_format((float) ($service_charge ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Net Amount</td>
                            <td class="details-value" style="color: #059669;">${{ number_format((float) ($net_amount ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Payment Method</td>
                            <td class="details-value">{{ $payment_method ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Wallet Address</td>
                            <td class="details-value">{{ $wallet_address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Approved Date</td>
                            <td class="details-value">
                                @if (! empty($payment_date))
                                    {{ \Illuminate\Support\Carbon::parse($payment_date)->format('d M Y, h:i A') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <p class="support-text">
                        If you have any questions about this withdrawal, please contact our support team.
                    </p>
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 8px; line-height: 1.5;">
                        Best regards,<br>
                        <strong style="color: #0f172a;">The {{ config('detailsApp.name') }} Team</strong>
                    </p>
                    <p style="font-size: 12px; margin-top: 15px;">
                        <a href="https://iroxfxglobal.com"
                            style="color: #10b981; text-decoration: none; font-weight: 700;">https://iroxfxglobal.com</a>
                    </p>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
