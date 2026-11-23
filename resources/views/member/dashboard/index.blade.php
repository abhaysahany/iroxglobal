@extends('member.layouts.app')
@section('title', 'Dashboard - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')

<style>
    .dashboard-snapshot-card-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        width: 100%;
        margin: 24px 0;
    }

    .glass-surface {
        background: rgba(255, 255, 255, 0.78);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(116, 185, 93, 0.25);
        box-shadow: 0 14px 35px rgba(35, 94, 34, 0.10);
    }

    .dashboard-snapshot-card {
        position: relative;
        overflow: hidden;
        min-height: 145px;
        padding: 20px 22px;
        border-radius: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.35s ease;
    }

    /* Modern Dual-Card for Random Wallet Address */
    .dashboard-snapshot-address {
        grid-column: 1 / -1;
        min-height: auto;
        padding: 22px 24px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 14px;
        border-radius: 20px;
        border: 1px solid var(--gl-border);
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
    }

    .dashboard-snapshot-address:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 45px rgba(35, 94, 34, 0.16);
        border-color: rgba(68, 157, 38, 0.42);
    }

    .dashboard-snapshot-address .wallet-card-header {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .dashboard-snapshot-address .wallet-card-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dashboard-snapshot-address .wallet-icon-box {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(79, 154, 36, 0.22), rgba(131, 226, 63, 0.10));
        border: 1px solid rgba(131, 226, 63, 0.32);
        color: var(--gl-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 0 20px rgba(131, 226, 63, 0.2);
    }

    body.theme-light .dashboard-snapshot-address .wallet-icon-box {
        background: linear-gradient(135deg, #eef9e9, #dff3d5);
        border-color: rgba(61, 154, 29, 0.2);
        color: #3d9a1d;
        box-shadow: 0 4px 12px rgba(61, 154, 29, 0.12);
    }

    .dashboard-snapshot-address .wallet-meta {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .dashboard-snapshot-address .wallet-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--gl-text);
        letter-spacing: 0.2px;
    }

    .dashboard-snapshot-address .wallet-subtitle {
        font-size: 12px;
        font-weight: 600;
        color: var(--gl-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dashboard-snapshot-address .wallet-network-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(79, 154, 36, 0.12);
        color: var(--gl-green);
        border: 1px solid rgba(79, 154, 36, 0.28);
    }

    body.theme-light .dashboard-snapshot-address .wallet-network-pill {
        background: #eef9e9;
        border-color: rgba(61, 154, 29, 0.25);
        color: #2f6f14;
    }

    /* Inner Dual-Card Container (The Well Card) */
    .dashboard-snapshot-address .wallet-address-well {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 18px;
        border-radius: 14px;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    body.theme-light .dashboard-snapshot-address .wallet-address-well {
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(73, 133, 36, 0.22);
        box-shadow: inset 0 2px 5px rgba(47, 111, 20, 0.05);
    }

    body.theme-dark .dashboard-snapshot-address .wallet-address-well {
        background: rgba(3, 14, 7, 0.65);
        border: 1px solid rgba(131, 226, 63, 0.20);
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.45);
    }

    .dashboard-snapshot-address .wallet-address-well:hover {
        border-color: rgba(131, 226, 63, 0.45);
        box-shadow: 0 4px 18px rgba(47, 111, 20, 0.12);
    }

    .dashboard-snapshot-address .wallet-address-content {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex: 1;
    }

    .dashboard-snapshot-address .wallet-chip-icon {
        color: var(--gl-green);
        font-size: 16px;
        opacity: 0.9;
        flex-shrink: 0;
    }

    body.theme-light .dashboard-snapshot-address .wallet-chip-icon {
        color: #3d9a1d;
    }

    .dashboard-snapshot-address .wallet-address-text {
        font-family: 'Space Grotesk', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: clamp(13px, 1.2vw, 15px);
        font-weight: 700;
        color: var(--gl-text);
        letter-spacing: 0.5px;
        overflow-wrap: anywhere;
        word-break: break-all;
        user-select: all;
    }

    .dashboard-snapshot-address .wallet-copy-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 96px;
        height: 38px;
        padding: 0 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        color: #ffffff;
        background: linear-gradient(135deg, #3d9a1d, #277011);
        box-shadow: 0 4px 14px rgba(45, 127, 23, 0.28);
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    .dashboard-snapshot-address .wallet-copy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(45, 127, 23, 0.42);
        background: linear-gradient(135deg, #46ad21, #2b7d14);
    }

    .dashboard-snapshot-address .wallet-copy-btn:active {
        transform: translateY(0);
    }

    .dashboard-snapshot-address .wallet-copy-btn.copied {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35) !important;
    }

    /* Responsive: Mobile, iPad, Desktop */
    @media (max-width: 767px) {
        .dashboard-snapshot-address {
            padding: 16px;
        }

        .dashboard-snapshot-address .wallet-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .dashboard-snapshot-address .wallet-network-pill {
            align-self: flex-start;
        }

        .dashboard-snapshot-address .wallet-address-well {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 12px 14px;
        }

        .dashboard-snapshot-address .wallet-copy-btn {
            width: 100%;
            height: 40px;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .dashboard-snapshot-address {
            padding: 18px 20px;
        }

        .dashboard-snapshot-address .wallet-address-text {
            font-size: 13px;
        }
    }

    .dashboard-snapshot-card::before {
        content: "";
        position: absolute;
        right: 20px;
        top: 58px;
        width: 88px;
        height: 88px;
        background: radial-gradient(circle, rgba(89, 185, 45, 0.14), rgba(89, 185, 45, 0.03));
        border-radius: 70% 30% 70% 30%;
        transform: rotate(-25deg);
        opacity: 0.75;
    }

    .dashboard-snapshot-card::after {
        content: "";
        position: absolute;
        right: 38px;
        top: 76px;
        width: 72px;
        height: 40px;
        background: rgba(76, 175, 80, 0.13);
        border-radius: 100% 0 100% 0;
        transform: rotate(28deg);
        opacity: 0.65;
    }

    .dashboard-snapshot-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(35, 94, 34, 0.16);
        border-color: rgba(68, 157, 38, 0.42);
    }

    .dashboard-snapshot-top {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dashboard-snapshot-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef9e9, #dff3d5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3d9a1d;
        font-size: 18px;
        box-shadow: inset 0 0 0 1px rgba(61, 154, 29, 0.08);
    }

    .dashboard-snapshot-card span {
        position: relative;
        z-index: 2;
        font-size: 14px;
        font-weight: 800;
        color: #102d12;
        line-height: 1.2;
    }

    .dashboard-snapshot-card strong {
        position: relative;
        z-index: 2;
        display: block;
        font-size: 30px;
        font-weight: 900;
        color: #0b2a0f;
        line-height: 1;
        letter-spacing: -1px;
        margin-top: 18px;
    }

    .dashboard-snapshot-card small {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 16px;
        font-size: 12px;
        font-weight: 600;
        color: #7c8b78;
    }

    .dashboard-snapshot-card small i,
    .dashboard-snapshot-card small b {
        color: #2ea51f;
        font-weight: 900;
    }

    .dashboard-snapshot-card small i {
        font-size: 13px;
    }

    .dashboard-half-card-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        width: 100%;
        margin: 0 0 24px;
    }

    .dashboard-half-card {
        position: relative;
        overflow: hidden;
        min-height: 150px;
        padding: 22px;
        border-radius: 20px;
        display: grid;
        grid-template-columns: 1fr;
        align-items: start;
        gap: 16px;
        transition: all 0.35s ease;
    }

    .dashboard-half-card::before {
        content: "";
        position: absolute;
        right: 28px;
        top: 34px;
        width: 120px;
        height: 90px;
        background: radial-gradient(circle, rgba(89, 185, 45, 0.15), rgba(89, 185, 45, 0.03));
        border-radius: 70% 30% 70% 30%;
        transform: rotate(-18deg);
        opacity: 0.8;
    }

    .dashboard-half-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 45px rgba(35, 94, 34, 0.16);
        border-color: rgba(68, 157, 38, 0.42);
    }

    .dashboard-half-card-left {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-width: 0;
    }

    .dashboard-half-card-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef9e9, #dff3d5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3d9a1d;
        font-size: 20px;
        box-shadow: inset 0 0 0 1px rgba(61, 154, 29, 0.08);
    }

    .dashboard-half-card span {
        display: block;
        color: #102d12;
        font-size: 16px;
        font-weight: 900;
        line-height: 1.2;
        overflow-wrap: anywhere;
        margin-top: 1px;
    }

    .dashboard-half-card small {
        display: block;
        margin-top: 6px;
        color: #3d9a1d;
        font-size: 12px;
        font-weight: 700;
    }

    .dashboard-half-card strong {
        position: relative;
        z-index: 2;
        color: #0b2a0f;
        font-size: clamp(28px, 2.35vw, 38px);
        font-weight: 900;
        line-height: 1;
        white-space: nowrap;
        justify-self: start;
        max-width: 100%;
        margin-top: 2px;
    }

    .dashboard-income-section {
        width: 100%;
        margin: 0 0 24px;
    }

    .dashboard-income-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 14px;
    }

    .dashboard-income-header span {
        display: block;
        color: #3d9a1d;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .dashboard-income-header h2 {
        margin: 4px 0 0;
        color: #102d12;
        font-size: 24px;
        font-weight: 900;
        line-height: 1.2;
    }

    .dashboard-income-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        width: 100%;
    }

    .dashboard-income-card {
        position: relative;
        overflow: hidden;
        min-height: 178px;
        padding: 20px;
        border-radius: 18px;
        transition: all 0.35s ease;
    }

    .dashboard-income-card::before {
        content: "";
        position: absolute;
        right: 16px;
        top: 46px;
        width: 88px;
        height: 78px;
        background: radial-gradient(circle, rgba(89, 185, 45, 0.14), rgba(89, 185, 45, 0.03));
        border-radius: 70% 30% 70% 30%;
        transform: rotate(-18deg);
        opacity: 0.74;
    }

    .dashboard-income-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 45px rgba(35, 94, 34, 0.16);
        border-color: rgba(68, 157, 38, 0.42);
    }

    .dashboard-income-top,
    .dashboard-income-values {
        position: relative;
        z-index: 2;
    }

    .dashboard-income-top {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .dashboard-income-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef9e9, #dff3d5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3d9a1d;
        font-size: 17px;
        box-shadow: inset 0 0 0 1px rgba(61, 154, 29, 0.08);
    }

    .dashboard-income-top span {
        color: #102d12;
        font-size: 14px;
        font-weight: 900;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .dashboard-income-values {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        margin-top: 20px;
    }

    .dashboard-income-values div {
        padding: 11px 12px;
        border-radius: 14px;
        background: rgba(238, 249, 233, 0.72);
        border: 1px solid rgba(116, 185, 93, 0.18);
    }

    .dashboard-income-values small {
        display: block;
        margin-bottom: 5px;
        color: #7c8b78;
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-income-values strong {
        display: block;
        color: #0b2a0f;
        font-size: 20px;
        font-weight: 900;
        line-height: 1;
        overflow-wrap: anywhere;
    }

    .pool-timer-card {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: 18px;
        min-height: 142px;
        width: 100%;
        padding: 24px 26px;
        border-radius: 22px;
        margin: 18px 0 0;
    }

    .pool-timer-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 88% 30%, rgba(89, 185, 45, 0.18), transparent 28%),
            linear-gradient(135deg, rgba(255, 255, 255, 0.88), rgba(239, 250, 233, 0.78));
        z-index: 0;
    }

    .pool-timer-card::after {
        content: "";
        position: absolute;
        right: 32px;
        bottom: -24px;
        width: 145px;
        height: 110px;
        background: rgba(76, 175, 80, 0.12);
        border-radius: 100% 0 100% 0;
        transform: rotate(24deg);
        z-index: 0;
    }

    .pool-timer-icon,
    .pool-timer-content,
    .pool-timer-status {
        position: relative;
        z-index: 1;
    }

    .pool-timer-icon {
        width: 58px;
        height: 58px;
        min-width: 58px;
        border-radius: 18px;
        background: linear-gradient(135deg, #3d9a1d, #226915);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 16px 28px rgba(45, 127, 23, 0.22);
    }

    .pool-timer-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #3d9a1d;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .pool-timer-value {
        display: block;
        color: #0b2a0f;
        font-size: clamp(18px, 2vw, 24px);
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: 0;
        overflow-wrap: anywhere;
    }

    .pool-timer-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        color: #6d8068;
        font-size: 13px;
        font-weight: 700;
    }

    .pool-timer-meta b,
    .pool-timer-meta i {
        color: #2c7f17;
    }

    .pool-timer-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        min-height: 40px;
        padding: 0 15px;
        border-radius: 999px;
        background: rgba(61, 154, 29, 0.12);
        color: #226915;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    .pool-timer-expired {
        color: #b42318;
    }

    .pool-timer-card .pool-timer-expired+.pool-timer-meta,
    .pool-timer-status.expired {
        background: rgba(180, 35, 24, 0.1);
        color: #b42318;
    }

    .dashboard-referral-strip {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: 18px;
        width: 100%;
        margin: 4px 0 0;
        padding: 18px 20px;
        border-radius: 18px;
    }

    .dashboard-referral-strip::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 12% 50%, rgba(89, 185, 45, 0.15), transparent 28%),
            linear-gradient(135deg, rgba(255, 255, 255, 0.88), rgba(239, 250, 233, 0.76));
        z-index: 0;
    }

    .dashboard-referral-title,
    .dashboard-referral-link,
    .dashboard-referral-copy,
    .dashboard-referral-alert {
        position: relative;
        z-index: 1;
    }

    .dashboard-referral-title {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 185px;
    }

    .dashboard-referral-title i {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef9e9, #dff3d5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3d9a1d;
        font-size: 17px;
    }

    .dashboard-referral-title span {
        display: block;
        color: #102d12;
        font-size: 15px;
        font-weight: 900;
        line-height: 1.15;
    }

    .dashboard-referral-title small {
        display: block;
        margin-top: 4px;
        color: #7c8b78;
        font-size: 11px;
        font-weight: 700;
    }

    .dashboard-referral-link {
        overflow: hidden;
        padding: 12px 14px;
        border: 1px solid rgba(116, 185, 93, 0.22);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        color: #102d12;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dashboard-referral-copy {
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #3d9a1d, #2c7f17);
        color: #ffffff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 0 18px;
        font-size: 13px;
        font-weight: 900;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .dashboard-referral-copy:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(45, 127, 23, 0.24);
    }

    .dashboard-referral-alert {
        grid-column: 1 / -1;
        display: none;
        margin: -4px 0 0;
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(61, 154, 29, 0.12);
        color: #226915;
        font-size: 13px;
        font-weight: 800;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 1399px) {
        .dashboard-income-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1199px) {
        .dashboard-snapshot-card-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .dashboard-half-card-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-income-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .dashboard-snapshot-card-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .dashboard-half-card-row {
            grid-template-columns: 1fr;
        }

        .dashboard-half-card {
            align-items: flex-start;
            grid-template-columns: 1fr;
            padding: 22px;
        }

        .dashboard-half-card strong {
            justify-self: start;
            font-size: 34px;
        }

        .dashboard-income-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-income-grid {
            grid-template-columns: 1fr;
        }

        .pool-timer-card {
            grid-template-columns: 1fr;
            align-items: flex-start;
            padding: 22px;
        }

        .pool-timer-status {
            justify-self: flex-start;
        }

        .dashboard-referral-strip {
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 18px;
        }

        .dashboard-referral-title {
            min-width: 0;
        }

        .dashboard-referral-link {
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .dashboard-snapshot-card {
            min-height: 155px;
            padding: 22px;
            border-radius: 18px;
        }

        .dashboard-snapshot-card strong {
            font-size: 36px;
        }

        .dashboard-snapshot-card span {
            font-size: 16px;
        }

        .dashboard-snapshot-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            font-size: 22px;
        }
    }

    .dashboard-page,
    .dashboard-page * {
        box-sizing: border-box;
    }

    .dashboard-page {
        overflow-x: hidden;
    }

    .dashboard-shell,
    .dashboard-content,
    .dashboard-snapshot-card,
    .dashboard-half-card,
    .dashboard-income-card,
    .pool-timer-card,
    .dashboard-referral-strip {
        max-width: 100%;
        min-width: 0;
    }

    .dashboard-snapshot-card strong,
    .dashboard-half-card strong,
    .dashboard-income-values strong,
    .pool-timer-value {
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    @media (max-width: 1500px) {

        .dashboard-snapshot-card-row,
        .dashboard-income-grid {
            gap: 14px;
        }

        .dashboard-snapshot-card,
        .dashboard-income-card {
            padding: 18px;
        }
    }

    @media (max-width: 1299px) {
        .dashboard-snapshot-card-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-half-card-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-half-card {
            padding: 20px;
        }

        .dashboard-half-card strong {
            font-size: clamp(26px, 2.6vw, 34px);
        }
    }

    @media (max-width: 1024px) {

        .dashboard-half-card-row,
        .dashboard-income-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .pool-timer-card {
            grid-template-columns: auto minmax(0, 1fr);
        }

        .pool-timer-status {
            grid-column: 2;
            justify-self: start;
        }

        .dashboard-referral-strip {
            grid-template-columns: minmax(0, 1fr) auto;
        }

        .dashboard-referral-title {
            grid-column: 1 / -1;
            min-width: 0;
        }
    }

    @media (max-width: 767px) {
        .dashboard-content {
            width: 100%;
            padding-left: 14px;
            padding-right: 14px;
        }

        .announcement-card,
        .pool-timer-card,
        .dashboard-snapshot-card,
        .dashboard-half-card,
        .dashboard-income-card,
        .dashboard-referral-strip {
            border-radius: 16px;
        }

        .pool-timer-card {
            gap: 14px;
            margin-top: 14px;
        }

        .pool-timer-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 16px;
            font-size: 21px;
        }

        .pool-timer-value {
            font-size: clamp(16px, 5vw, 20px);
            line-height: 1.2;
        }

        .pool-timer-meta {
            align-items: flex-start;
            flex-wrap: wrap;
            font-size: 12px;
        }

        .dashboard-snapshot-card-row,
        .dashboard-half-card-row,
        .dashboard-income-grid {
            grid-template-columns: 1fr;
            gap: 14px;
            margin: 18px 0;
        }

        .dashboard-snapshot-card {
            min-height: 136px;
        }

        .dashboard-snapshot-card strong {
            font-size: clamp(28px, 9vw, 36px);
            letter-spacing: 0;
        }

        .dashboard-half-card {
            min-height: auto;
            gap: 14px;
        }

        .dashboard-half-card-left {
            align-items: flex-start;
        }

        .dashboard-half-card strong {
            font-size: clamp(28px, 9vw, 36px);
        }

        .dashboard-income-header h2 {
            font-size: 21px;
        }

        .dashboard-income-card {
            min-height: auto;
            padding: 18px;
        }

        .dashboard-income-values {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .dashboard-income-values strong {
            font-size: 18px;
        }

        .dashboard-referral-strip {
            grid-template-columns: 1fr;
        }

        .dashboard-referral-link {
            font-size: 12px;
            line-height: 1.45;
        }

        .dashboard-referral-copy {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .dashboard-content {
            padding-left: 10px;
            padding-right: 10px;
        }

        .pool-timer-card,
        .dashboard-snapshot-card,
        .dashboard-half-card,
        .dashboard-income-card,
        .dashboard-referral-strip {
            padding: 16px;
        }

        .pool-timer-icon,
        .dashboard-snapshot-icon,
        .dashboard-half-card-icon,
        .dashboard-income-icon,
        .dashboard-referral-title i {
            width: 42px;
            height: 42px;
            min-width: 42px;
            font-size: 17px;
        }

        .pool-timer-value {
            font-size: 15px;
        }

        .dashboard-snapshot-card strong,
        .dashboard-half-card strong {
            font-size: clamp(26px, 8vw, 32px);
        }

        .dashboard-income-values {
            grid-template-columns: 1fr;
        }

        .dashboard-income-values div {
            padding: 10px;
        }
    }

    @media (max-width: 360px) {

        .pool-timer-card,
        .dashboard-snapshot-card,
        .dashboard-half-card,
        .dashboard-income-card,
        .dashboard-referral-strip {
            padding: 14px;
        }

        .dashboard-snapshot-top,
        .dashboard-half-card-left,
        .dashboard-income-top,
        .dashboard-referral-title {
            gap: 10px;
        }

        .dashboard-referral-copy {
            min-height: 42px;
            padding: 0 12px;
        }
    }

    /* Dark mode overrides for dashboard widgets */
    body.theme-dark .dashboard-snapshot-card span,
    body.theme-dark .dashboard-half-card span,
    body.theme-dark .dashboard-income-header h2,
    body.theme-dark .dashboard-income-top span,
    body.theme-dark .dashboard-referral-title span {
        color: var(--gl-text);
    }

    body.theme-dark .dashboard-snapshot-card strong,
    body.theme-dark .dashboard-half-card strong,
    body.theme-dark .dashboard-income-values strong,
    body.theme-dark .pool-timer-value {
        color: var(--gl-text);
    }

    body.theme-dark .dashboard-snapshot-card small,
    body.theme-dark .pool-timer-meta,
    body.theme-dark .dashboard-referral-title small {
        color: var(--gl-muted);
    }

    body.theme-dark .dashboard-income-values div {
        background: rgba(8, 22, 13, 0.48);
        border-color: var(--gl-border);
    }

    body.theme-dark .dashboard-referral-link {
        background: rgba(8, 22, 13, 0.48);
        border-color: var(--gl-border);
        color: var(--gl-text);
    }

    body.theme-dark .pool-timer-card::before {
        background:
            radial-gradient(circle at 88% 30%, rgba(131, 226, 63, 0.12), transparent 28%),
            linear-gradient(135deg, rgba(8, 22, 13, 0.88), rgba(6, 18, 10, 0.78));
    }

    body.theme-dark .dashboard-referral-strip::before {
        background:
            radial-gradient(circle at 12% 50%, rgba(131, 226, 63, 0.12), transparent 28%),
            linear-gradient(135deg, rgba(8, 22, 13, 0.88), rgba(6, 18, 10, 0.76));
    }

    body.theme-dark .dashboard-snapshot-icon,
    body.theme-dark .dashboard-half-card-icon,
    body.theme-dark .dashboard-income-icon,
    body.theme-dark .dashboard-referral-title i {
        background: linear-gradient(135deg, #07160a, #0b2210);
        color: #83e23f;
        box-shadow: inset 0 0 0 1px rgba(131, 226, 63, 0.15);
    }

    body.theme-dark .pool-timer-status {
        background: rgba(131, 226, 63, 0.15);
        color: #83e23f;
    }

    body.theme-dark .pool-timer-status.expired {
        background: rgba(255, 107, 122, 0.15);
        color: #ff6b7a;
    }

    .pool-timer-pending {
        color: #2563eb;
    }

    body.theme-dark .pool-timer-pending {
        color: #60a5fa;
    }

    .pool-timer-status.pending {
        background: rgba(37, 99, 235, 0.12);
        color: #2563eb;
    }

    body.theme-dark .pool-timer-status.pending {
        background: rgba(96, 165, 250, 0.15);
        color: #60a5fa;
    }

    .pool-timer-card.pending .pool-timer-icon {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        box-shadow: 0 16px 28px rgba(37, 99, 235, 0.22) !important;
    }

    .pool-timer-card.pending .pool-timer-label {
        color: #2563eb !important;
    }

    body.theme-dark .pool-timer-card.pending .pool-timer-label {
        color: #60a5fa !important;
    }

    /* Pending (Blue Theme) Card Design - Dashed Border & Glass Style */
    .pool-timer-card.pending {
        border: 2px dashed rgba(37, 99, 235, 0.40) !important;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.06) !important;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.65), rgba(240, 247, 255, 0.55)) !important;
    }

    .pool-timer-card.pending::before {
        background:
            radial-gradient(circle at 88% 30%, rgba(37, 99, 235, 0.10), transparent 35%) !important;
    }

    .pool-timer-card.pending::after {
        background: rgba(37, 99, 235, 0.04) !important;
    }

    /* Dark Mode Pending */
    body.theme-dark .pool-timer-card.pending {
        border: 2px dashed rgba(96, 165, 250, 0.30) !important;
        box-shadow: none !important;
        background: linear-gradient(135deg, rgba(8, 16, 28, 0.45), rgba(5, 10, 18, 0.35)) !important;
    }

    body.theme-dark .pool-timer-card.pending::before {
        background:
            radial-gradient(circle at 88% 30%, rgba(96, 165, 250, 0.08), transparent 35%) !important;
    }

    body.theme-dark .pool-timer-card.pending::after {
        background: rgba(96, 165, 250, 0.03) !important;
    }

    /* Live Pulsing Dot Animation */
    .status-pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #2563eb;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }

    body.theme-dark .status-pulse-dot {
        background-color: #60a5fa;
    }

    .status-pulse-dot::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: inherit;
        border-radius: 50%;
        top: 0;
        left: 0;
        animation: statusPulse 1.8s infinite ease-in-out;
    }

    @keyframes statusPulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(2.6);
            opacity: 0;
        }
    }

    /* Completed (Gold Theme) Card Design */
    .pool-timer-card.completed {
        border-color: rgba(214, 169, 58, 0.30) !important;
        box-shadow: 0 14px 35px rgba(214, 169, 58, 0.08) !important;
    }

    .pool-timer-card.completed::before {
        background:
            radial-gradient(circle at 88% 30%, rgba(214, 169, 58, 0.15), transparent 28%),
            linear-gradient(135deg, rgba(255, 255, 255, 0.90), rgba(253, 248, 225, 0.80)) !important;
    }

    .pool-timer-card.completed::after {
        background: rgba(214, 169, 58, 0.08) !important;
    }

    .pool-timer-card.completed .pool-timer-icon {
        background: linear-gradient(135deg, #f5c85b, #d6a93a) !important;
        box-shadow: 0 16px 28px rgba(214, 169, 58, 0.22) !important;
    }

    .pool-timer-card.completed .pool-timer-label {
        color: #d6a93a !important;
    }

    .pool-timer-completed {
        color: #d6a93a !important;
        font-size: clamp(24px, 2.5vw, 32px) !important;
    }

    .pool-timer-status.completed {
        background: rgba(214, 169, 58, 0.12);
        color: #d6a93a;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        min-height: 40px;
        padding: 0 15px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    /* Dark Mode Completed */
    body.theme-dark .pool-timer-card.completed {
        border-color: rgba(245, 200, 91, 0.25) !important;
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.3) !important;
    }

    body.theme-dark .pool-timer-card.completed::before {
        background:
            radial-gradient(circle at 88% 30%, rgba(245, 200, 91, 0.10), transparent 28%),
            linear-gradient(135deg, rgba(18, 14, 5, 0.88), rgba(12, 9, 4, 0.78)) !important;
    }

    body.theme-dark .pool-timer-card.completed::after {
        background: rgba(245, 200, 91, 0.06) !important;
    }

    body.theme-dark .pool-timer-card.completed .pool-timer-label {
        color: #f5c85b !important;
    }

    body.theme-dark .pool-timer-completed {
        color: #f5c85b !important;
    }

    body.theme-dark .pool-timer-status.completed {
        background: rgba(245, 200, 91, 0.15);
        color: #f5c85b;
    }

    /* Isolated Bootstrap Modal CSS (No global resets) */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1055;
        display: none;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translate(0, -50px);
    }

    .modal.show .modal-dialog {
        transform: none;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 0.5rem;
        pointer-events: none;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }
    }

    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.5rem;
        outline: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    body.theme-dark .modal-content {
        background-color: #1e293b;
        border-color: rgba(255, 255, 255, 0.1);
        color: #f8fafc;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .modal-backdrop.fade {
        opacity: 0;
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    .modal-header {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1rem;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: calc(0.5rem - 1px);
        border-top-right-radius: calc(0.5rem - 1px);
    }

    body.theme-dark .modal-header {
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    .modal-title {
        margin-bottom: 0;
        line-height: 1.5;
        font-size: 1.125rem;
        font-weight: 600;
    }

    .btn-close {
        box-sizing: content-box;
        width: 1em;
        height: 1em;
        padding: 0.25em 0.25em;
        color: #000;
        background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
        border: 0;
        border-radius: 0.375rem;
        opacity: 0.5;
        cursor: pointer;
    }

    body.theme-dark .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .btn-close:hover {
        opacity: 0.75;
    }

    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1rem;
    }

    .modal-footer {
        display: flex;
        flex-wrap: wrap;
        flex-shrink: 0;
        align-items: center;
        justify-content: flex-end;
        padding: 0.75rem;
        border-top: 1px solid #dee2e6;
        border-bottom-right-radius: calc(0.5rem - 1px);
        border-bottom-left-radius: calc(0.5rem - 1px);
    }

    body.theme-dark .modal-footer {
        border-top-color: rgba(255, 255, 255, 0.1);
    }

    .modal-footer>* {
        margin: 0.25rem;
    }
</style>

@php
    $poolDate = $data->pool_date ?? null;
    $poolExpireAt = null;
    $dashboardMemberId = $data['memberid'] ?? ($data->memberid ?? null);
    $address =
        $data['random_address'] ??
        ($data->random_address ?? ($data['random_address'] ?? ($data->random_address ?? null)));
    $addonMyDirect = (int) ($addonData?->my_direct ?? 0);
    $addonTotalTeam = (int) ($addonData?->total_team ?? 0);
    $addonGlobalMembers = (int) ($addonData?->global_members ?? 0);
    $addonTotalWithdrawal = (float) ($addonData?->total_withdrawal ?? 0);
    $addonP2pWallet = (float) ($addonData?->p2p_wallet ?? 0);
    $addonRankIncome = (float) ($addonData?->rank_income ?? 0);
    $addonDailyIncome = (float) ($addonData?->daily_income ?? 0);
    $addonReferralIncome = (float) ($addonData?->referral_income ?? 0);
    $addonLevelIncome = (float) ($addonData?->level_income ?? 0);
    $addonGlobalRecIncome = (float) ($addonData?->global_rec_income ?? 0);
    $addonGlobalRecStarIncome = (float) ($addonData?->global_rec_star_income ?? 0);
    $addonTotalIncome =
        $addonRankIncome + $addonDailyIncome + $addonReferralIncome + $addonLevelIncome + $addonGlobalRecIncome + $addonGlobalRecStarIncome;
    $dashboardIncomeCards = [
        [
            'label' => 'Total Overview All Incomes',
            'icon' => 'fa-chart-pie',
            'total' => totalIncome($dashboardMemberId) + $addonTotalIncome,
            'today' => todayIncome($dashboardMemberId),
        ],
        [
            'label' => 'Referral Income',
            'icon' => 'fa-hand-holding-dollar',
            'total' => totalReferralIncome($dashboardMemberId) + $addonReferralIncome,
            'today' => todayReferralIncome($dashboardMemberId),
        ],
        [
            'label' => 'Level Income',
            'icon' => 'fa-layer-group',
            'total' => totalLevelIncome($dashboardMemberId) + $addonLevelIncome,
            'today' => todayLevelIncome($dashboardMemberId),
        ],
        [
            'label' => 'Daily Income',
            'icon' => 'fa-calendar-day',
            'total' => totalDailyIncome($dashboardMemberId) + $addonDailyIncome,
            'today' => todayDailyIncome($dashboardMemberId),
        ],
        [
            'label' => 'Rank Income',
            'icon' => 'fa-ranking-star',
            'total' => totalRankIncome($dashboardMemberId) + $addonRankIncome,
            'today' => todayRankIncome($dashboardMemberId),
        ],
        [
            'label' => 'Global Rec Income',
            'icon' => 'fa-earth-americas',
            'total' => totalGlobalRacIncome($dashboardMemberId) + $addonGlobalRecIncome,
            'today' => todayGlobalRacIncome($dashboardMemberId),
        ],
        [
            'label' => 'Global Rec Star Income',
            'icon' => 'fa-star',
            'total' => totalGlobalRacStarIncome($dashboardMemberId) + $addonGlobalRecStarIncome,
            'today' => todayGlobalRacStarIncome($dashboardMemberId),
        ],
    ];

    if (!empty($poolDate)) {
        try {
            if ($data->pool_status == 0) {
                $poolExpireAt = \Carbon\Carbon::parse($poolDate)->addDays(10);
            } elseif ($data->pool_status == 1) {
                $poolExpireAt = \Carbon\Carbon::parse($poolDate)->addDays(30);
            }
        } catch (\Exception $e) {
            $poolExpireAt = null;
        }
    }
    if ($data['pool_status'] == 0) {
        $timText = 'Global Rec Income';
    } elseif ($data['pool_status'] == 1) {
        $timText = 'Global Rec Star Income';
    } else {
        $timText = 'Global Rec Pool Income';
    }
@endphp

@section('content')
    <main class="member-page dashboard-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content">
                @if (session('success'))
                    <div class="glass-alert success dashboard-alert">{{ session('success') }}</div>
                @endif

                <article class="announcement-card glass-surface">
                    <span class="announcement-icon">
                        <i class="fa-solid fa-bullhorn"></i>
                    </span>
                    <div class="announcement-track">
                        <marquee>
                            <h5 class="announcement-message-list">
                                @foreach ($dashMsg as $item)
                                    <span class="announcement-message">{{ $item['message'] }}</span>
                                @endforeach
                            </h5>
                        </marquee>
                    </div>
                </article>
                @if (poolTimer($data['memberid']) == 'on' && $data['pool_status'] < 2)
                    <article class="pool-timer-card glass-surface {{ !$poolExpireAt ? 'pending' : '' }}">
                        <div class="pool-timer-icon">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div class="pool-timer-content">
                            <span class="pool-timer-label">
                                <i class="fa-solid fa-clock"></i>
                                {{ $timText }}
                            </span>
                            @if ($poolExpireAt)
                                <strong class="pool-timer-value js-pool-countdown"
                                    data-pool-expire-at="{{ $poolExpireAt->toIso8601String() }}">--:--:--:--</strong>
                                <small class="pool-timer-meta">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <b>Expire Date:</b> {{ $poolExpireAt->format('d M Y h:i A') }}
                                </small>
                            @else
                                <strong class="pool-timer-value pool-timer-pending">START AFTER 2 DIRECTS</strong>
                                <small class="pool-timer-meta">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <b>{{ $timText }}</b> not available
                                </small>
                            @endif
                        </div>
                        @if ($poolExpireAt)
                            <div class="pool-timer-status js-pool-status">
                                <i class="fa-solid fa-seedling"></i>
                                {{ $timText }} Active
                            </div>
                        @else
                            <div class="pool-timer-status pending" style="gap: 8px;">
                                <span class="status-pulse-dot"></span>
                                Not Started
                            </div>
                        @endif
                    </article>
                @elseif($data['pool_status'] == 2)
                    <article class="pool-timer-card glass-surface completed">
                        <div class="pool-timer-icon">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <div class="pool-timer-content">
                            <span class="pool-timer-label">
                                <i class="fa-solid fa-clock"></i>
                                {{ $timText }}
                            </span>
                            <strong class="pool-timer-value pool-timer-completed">ALREADY COMPLETED</strong>
                            <small class="pool-timer-meta">
                                <i class="fa-solid fa-circle-check"></i>
                                Congratulations! You have successfully qualified for this pool.
                            </small>
                        </div>
                        <div class="pool-timer-status completed">
                            <i class="fa-solid fa-circle-check"></i>
                            Completed
                        </div>
                    </article>
                @endif


                <section class="dashboard-snapshot-card-row">

                    <div class="dashboard-snapshot-card dashboard-snapshot-address glass-surface">
                        <div class="wallet-card-header">
                            <div class="wallet-card-header-left">
                                <div class="wallet-icon-box">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div class="wallet-meta">
                                    <span class="wallet-title">Address</span>
                                    {{-- <span class="wallet-subtitle">
                                        <span class="status-pulse-dot"></span>
                                        BEP-20 &bull; BNB Smart Chain
                                    </span> --}}
                                </div>
                            </div>
                            {{-- <div class="wallet-network-pill">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Active Wallet</span>
                            </div> --}}
                        </div>

                        <div class="wallet-address-well">
                            <div class="wallet-address-content">
                                <i class="fa-solid fa-shield-halved wallet-chip-icon"></i>
                                <span class="wallet-address-text" id="memberWalletAddressVal"
                                    title="{{ $address ?? 'N/A' }}">{{ $address ?? 'N/A' }}</span>
                            </div>
                            {{-- <button type="button" class="wallet-copy-btn" id="btnCopyMemberWallet" onclick="copyDashboardWalletAddress()">
                                <i class="fa-regular fa-copy" id="walletCopyBtnIcon"></i>
                                <span id="walletCopyBtnLabel">Copy</span>
                            </button> --}}
                        </div>
                    </div>

                    <div class="dashboard-snapshot-card glass-surface">
                        <div class="dashboard-snapshot-top">
                            <div class="dashboard-snapshot-icon">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <span>My Directs</span>
                        </div>
                        <strong>{{ (int) $data['downline'] + $addonMyDirect }}</strong>
                        <small>
                            <i class="fa-solid fa-arrow-up"></i>
                            <b>Total</b> Directs
                        </small>
                    </div>
                    <div class="dashboard-snapshot-card glass-surface">
                        <div class="dashboard-snapshot-top">
                            <div class="dashboard-snapshot-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <span>Total Team</span>
                        </div>
                        <strong>{{ (int) $data['team'] + $addonTotalTeam }}</strong>
                        <small>
                            <i class="fa-solid fa-arrow-up"></i>
                            <b>Total</b> Team
                        </small>
                    </div>

                    <div class="dashboard-snapshot-card glass-surface">
                        <div class="dashboard-snapshot-top">
                            <div class="dashboard-snapshot-icon">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <span>Global Members</span>
                        </div>
                        <strong>{{ (int) $data['weekly_count'] + $addonGlobalMembers }}</strong>
                        <small>
                            <i class="fa-solid fa-arrow-up"></i>
                            <b>Total</b> Global Members
                        </small>
                    </div>

                    <div class="dashboard-snapshot-card glass-surface">
                        <div class="dashboard-snapshot-top">
                            <div class="dashboard-snapshot-icon">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <span>Main Wallet</span>
                        </div>
                        <strong>$ {{ number_format($data->wallet, 2) }}</strong>
                        <small>
                            <i class="fa-solid fa-arrow-up"></i>
                            <b>Total</b> Wallet Balance
                        </small>
                    </div>

                </section>

                <section class="dashboard-half-card-row">
                    <div class="dashboard-half-card glass-surface">
                        <div class="dashboard-half-card-left">
                            <div class="dashboard-half-card-icon">
                                <i class="fa-solid fa-circle-down"></i>
                            </div>
                            <div>
                                <span>Total Deposit</span>
                                <small>Total deposited amount</small>
                            </div>
                        </div>
                        <strong>${{ number_format(totalDeposit($data['memberid']), 2) }}</strong>
                    </div>

                    <div class="dashboard-half-card glass-surface">
                        <div class="dashboard-half-card-left">
                            <div class="dashboard-half-card-icon">
                                <i class="fa-solid fa-circle-up"></i>
                            </div>
                            <div>
                                <span>Total withdrawal</span>
                                <small>Total withdrawal amount</small>
                            </div>
                        </div>
                        <strong>${{ number_format(totalWithdrawals($data['memberid']) + $addonTotalWithdrawal, 2) }}</strong>
                    </div>

                    <div class="dashboard-half-card glass-surface">
                        <div class="dashboard-half-card-left">
                            <div class="dashboard-half-card-icon">
                                <i class="fa-solid fa-right-left"></i>
                            </div>
                            <div>
                                <span>P2P Wallet</span>
                                <small>Total P2P wallet amount</small>
                            </div>
                        </div>
                        <strong>${{ number_format((float) $data->p2p_wallet + $addonP2pWallet, 2) }}</strong>
                    </div>
                </section>

                <section class="dashboard-income-section">
                    <div class="dashboard-income-header">
                        <div>
                            <span>Income Overview</span>
                            <h2>Total & Today's Incomes</h2>
                        </div>
                    </div>

                    <div class="dashboard-income-grid">
                        @foreach ($dashboardIncomeCards as $incomeCard)
                            <article class="dashboard-income-card glass-surface">
                                <div class="dashboard-income-top">
                                    <div class="dashboard-income-icon">
                                        <i class="fa-solid {{ $incomeCard['icon'] }}"></i>
                                    </div>
                                    <span>{{ $incomeCard['label'] }}</span>
                                </div>

                                <div class="dashboard-income-values">
                                    <div>
                                        <small>Total Income</small>
                                        <strong>${{ (float)$incomeCard['total'] == round((float)$incomeCard['total'], 2) ? number_format((float) $incomeCard['total'], 2) : rtrim(rtrim(number_format((float) $incomeCard['total'], 5), '0'), '.') }}</strong>
                                    </div>
                                    <div>
                                        <small>Today Income</small>
                                        <strong>${{ (float)$incomeCard['today'] == round((float)$incomeCard['today'], 2) ? number_format((float) $incomeCard['today'], 2) : rtrim(rtrim(number_format((float) $incomeCard['today'], 5), '0'), '.') }}</strong>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="dashboard-referral-strip glass-surface">
                    <div class="dashboard-referral-title">
                        <i class="fa-solid fa-link"></i>
                        <div>
                            <span>Referral Link</span>
                            <small>Share your joining link</small>
                        </div>
                    </div>
                    <div class="dashboard-referral-link" id="code">
                        {{ url('signup/' . $data['memberid']) }}
                    </div>
                    <button type="button" class="dashboard-referral-copy" id="copyButtonId">
                        <i class="fa-solid fa-copy"></i>
                        <span id="msgText">Copy Link</span>
                    </button>
                    <div class="dashboard-referral-alert" id="rcode">
                        Copied! Referral link copied successfully
                    </div>
                </section>

            </div>
        </section>
    </main>
    @if (session()->has('dashImgModel1') && isset($imgData) && $imgData['status'] == 'Live')
        <div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">{{ $imgData['title'] }}</h6>
                        <button type="button" class="btn-close clearSession" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ url('https://admin.iroxfxglobal.com/admin_assets/images/dash-img') }}/{{ $imgData['image'] }}"
                            style="width:100%">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary clearSession"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var timer = document.querySelector('.js-pool-countdown');
            var poolStatus = document.querySelector('.js-pool-status');

            function pad(value) {
                return String(value).padStart(2, '0');
            }

            if (timer) {
                var expireAt = new Date(timer.getAttribute('data-pool-expire-at')).getTime();

                function renderTimer() {
                    var distance = expireAt - Date.now();

                    if (!Number.isFinite(expireAt) || distance <= 0) {
                        timer.textContent = 'Timer Expire';
                        timer.classList.add('pool-timer-expired');

                        if (poolStatus) {
                            poolStatus.classList.add('expired');
                            poolStatus.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Expired';
                        }

                        return;
                    }

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                    var minutes = Math.floor((distance / (1000 * 60)) % 60);
                    var seconds = Math.floor((distance / 1000) % 60);

                    timer.textContent = days + 'd ' + pad(hours) + 'h ' + pad(minutes) + 'm ' + pad(seconds) + 's';
                    window.setTimeout(renderTimer, 1000);
                }

                renderTimer();
            }

            var copyButton = document.getElementById('copyButtonId');
            var code = document.getElementById('code');
            var message = document.getElementById('rcode');
            var messageText = document.getElementById('msgText');

            if (copyButton && code) {
                copyButton.addEventListener('click', function() {
                    var referralLink = code.textContent.trim();

                    navigator.clipboard.writeText(referralLink).then(function() {
                        if (message) {
                            message.style.display = 'block';
                        }

                        if (messageText) {
                            messageText.textContent = 'Copied';
                        }

                        window.setTimeout(function() {
                            if (message) {
                                message.style.display = 'none';
                            }

                            if (messageText) {
                                messageText.textContent = 'Copy Link';
                            }
                        }, 1800);
                    });
                });
            }
        });

        function copyDashboardWalletAddress() {
            var addrEl = document.getElementById('memberWalletAddressVal');
            var btn = document.getElementById('btnCopyMemberWallet');
            var icon = document.getElementById('walletCopyBtnIcon');
            var label = document.getElementById('walletCopyBtnLabel');

            if (!addrEl || !btn) return;
            var text = addrEl.textContent.trim();
            if (!text || text === 'N/A') return;

            function markCopied() {
                btn.classList.add('copied');
                if (icon) {
                    icon.className = 'fa-solid fa-check';
                }
                if (label) {
                    label.textContent = 'Copied!';
                }
                setTimeout(function() {
                    btn.classList.remove('copied');
                    if (icon) {
                        icon.className = 'fa-regular fa-copy';
                    }
                    if (label) {
                        label.textContent = 'Copy';
                    }
                }, 2000);
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(markCopied).catch(function() {
                    fallbackCopy(text);
                });
            } else {
                fallbackCopy(text);
            }

            function fallbackCopy(val) {
                var tempInput = document.createElement('textarea');
                tempInput.value = val;
                tempInput.style.position = 'fixed';
                tempInput.style.opacity = '0';
                document.body.appendChild(tempInput);
                tempInput.focus();
                tempInput.select();
                try {
                    document.execCommand('copy');
                    markCopied();
                } catch (e) {}
                document.body.removeChild(tempInput);
            }
        }
    </script>
    @if (session()->has('dashImgModel1') && isset($imgData) && $imgData['status'] == 'Live')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#myModal').modal('show');

                $('.clearSession').on('click', function() {
                    {{ session()->forget('dashImgModel1') }};
                })
            });
        </script>
    @endif
@endsection
