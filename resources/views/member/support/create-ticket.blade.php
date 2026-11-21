@extends('member.layouts.app')

@section('title', 'Create Ticket - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Create Ticket')

@section('content')
<main class="member-page dashboard-page support-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            <!-- BREADCRUMB -->
            <!--<div class="support-breadcrumb" style="margin-bottom: 20px; font-size: 13px; font-weight: 700; color: var(--gl-muted);">-->
            <!--    Demo Wealth Portal / Create Support Ticket-->
            <!--</div>-->

            <!-- HERO SUMMARY CARD -->
            <article class="support-hero glass-surface" style="border-radius: 16px; border: 1px solid var(--gl-border); background: linear-gradient(135deg, rgba(5, 15, 8, 0.65), rgba(73, 133, 36, 0.1)); box-shadow: var(--gl-shadow); backdrop-filter: blur(10px); padding: 30px;">
                <div class="support-hero-content" style="display: flex; justify-content: space-between; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div class="support-hero-left" style="flex: 1 1 450px;">
                        <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">CUSTOMER SUPPORT</span>
                        <h1 class="support-title" style="font-size: 32px; font-weight: 800; color: var(--gl-gold); margin: 0 0 12px;">Create Ticket</h1>
                        <p class="support-desc" style="font-size: 14px; line-height: 1.5; color: var(--gl-text); margin: 0 0 20px;">Submit a new support ticket for any issue or inquiry. Our team will respond as soon as possible.</p>
                        <div class="support-meta" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Support</span>
                            <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">24/7 Available</span>
                        </div>
                    </div>
                    <div class="support-hero-right" style="flex: 1 1 320px;">
                        <div class="support-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">
                            <div class="support-stat-card glass-surface" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft);">
                                <i class="fa-solid fa-user-tag" style="color: var(--gl-gold); font-size: 18px;"></i>
                                <div style="display: flex; flex-direction: column;">
                                    <small style="font-size: 10px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Member ID</small>
                                    <strong style="font-size: 14px; font-weight: 800; color: var(--gl-text);">{{ $data->memberid ?? '' }}</strong>
                                </div>
                            </div>
                            <div class="support-stat-card glass-surface" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft);">
                                <i class="fa-solid fa-reply-all" style="color: var(--gl-green); font-size: 18px;"></i>
                                <div style="display: flex; flex-direction: column;">
                                    <small style="font-size: 10px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Response</small>
                                    <strong style="font-size: 14px; font-weight: 800; color: var(--gl-text);">Within 24h</strong>
                                </div>
                            </div>
                            <div class="support-stat-card glass-surface" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--gl-border); background: var(--gl-panel-soft); grid-column: span 2;">
                                <i class="fa-solid fa-circle-check" style="color: #06b6d4; font-size: 18px;"></i>
                                <div style="display: flex; flex-direction: column;">
                                    <small style="font-size: 10px; font-weight: 700; color: var(--gl-muted); text-transform: uppercase;">Status</small>
                                    <strong style="font-size: 14px; font-weight: 800; color: var(--gl-text);">Online Support</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- FORM CARD -->
            <article class="support-form-card glass-surface" style="margin-top: 24px; max-width: 680px; margin-left: auto; margin-right: auto; padding: 40px 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel); box-shadow: var(--gl-shadow);">
                <div class="support-form-head" style="text-align: center; margin-bottom: 30px;">
                    <div class="support-icon-wrap" style="width: 64px; height: 64px; border-radius: 50%; background: rgba(73, 133, 36, 0.1); border: 2px solid var(--gl-green); display: grid; place-items: center; margin: 0 auto 16px; box-shadow: 0 0 20px rgba(73, 133, 36, 0.2);">
                        <i class="fa-solid fa-headset" style="color: var(--gl-green); font-size: 24px;"></i>
                    </div>
                    <h2 class="support-title" style="font-size: 20px; font-weight: 800; color: var(--gl-gold); margin: 0 0 4px;">Create New Support Ticket</h2>
                    <p style="font-size: 13px; font-weight: 700; color: var(--gl-muted); margin: 0 0 12px;">Global Portal Support Desk</p>
                    <div class="support-member-id" style="font-size: 24px; font-weight: 800; color: #06b6d4; letter-spacing: 0.5px;">
                        {{ $data->memberid ?? '' }}
                    </div>
                </div>

                <!-- ALERTS FOR SUCCESS/ERRORS -->
                @if (session('createMsg'))
                    <div class="support-alert alert-success" style="padding: 12px 16px; border-radius: 8px; border: 1px solid var(--gl-green); background: rgba(73, 133, 36, 0.1); color: var(--gl-green); font-size: 13.5px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('createMsg') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="support-alert alert-danger" style="padding: 12px 16px; border-radius: 8px; border: 1px solid var(--gl-danger); background: rgba(220, 53, 69, 0.1); color: var(--gl-danger); font-size: 13.5px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-circle-xmark"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="support-error" style="padding: 12px 16px; border-radius: 8px; border: 1px solid var(--gl-danger); background: rgba(220, 53, 69, 0.05); color: var(--gl-danger); font-size: 13px; font-weight: 600; margin-bottom: 24px;">
                        <ul style="margin: 0; padding-left: 16px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form action="{{ route('member.support.create-ticket.submit') }}" method="POST" class="support-form" style="display: flex; flex-direction: column; gap: 20px;">
                    @csrf
                    
                    <div class="support-field" style="display: flex; flex-direction: column; gap: 8px;">
                        <label for="member_id" class="support-label" style="font-size: 12.5px; font-weight: 800; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">Member ID</label>
                        <input type="text" name="userid" id="member_id" class="support-input" value="{{ $data->memberid ?? '' }}" readonly placeholder="Enter Member ID" style="padding: 12px 16px; border-radius: 10px; border: 1px solid var(--gl-border); background: rgba(5, 15, 8, 0.45); color: var(--gl-text); font-weight: 600; font-size: 14px; outline: none; transition: border-color 0.2s; cursor: not-allowed; box-shadow: inset 0 2px 4px rgba(0,0,0,0.15);">
                    </div>

                    <div class="support-field" style="display: flex; flex-direction: column; gap: 8px;">
                        <label for="subject" class="support-label" style="font-size: 12.5px; font-weight: 800; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">Subject</label>
                        <input type="text" name="subject" id="subject" class="support-input" value="{{ old('subject') }}" placeholder="Enter Subject" required style="padding: 12px 16px; border-radius: 10px; border: 1px solid var(--gl-border); background: rgba(5, 15, 8, 0.45); color: var(--gl-text); font-weight: 600; font-size: 14px; outline: none; transition: border-color 0.2s, box-shadow 0.2s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.15);">
                    </div>

                    <div class="support-field" style="display: flex; flex-direction: column; gap: 8px;">
                        <label for="issue" class="support-label" style="font-size: 12.5px; font-weight: 800; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">Particular / Issue</label>
                        <textarea name="issue" id="issue" rows="6" class="support-textarea" placeholder="Enter Your Issue" required style="padding: 14px 16px; border-radius: 10px; border: 1px solid var(--gl-border); background: rgba(5, 15, 8, 0.45); color: var(--gl-text); font-weight: 600; font-size: 14px; outline: none; transition: border-color 0.2s, box-shadow 0.2s; resize: vertical; box-shadow: inset 0 2px 4px rgba(0,0,0,0.15);">{{ old('issue') }}</textarea>
                    </div>

                    <div class="support-actions" style="margin-top: 10px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="support-btn" style="min-height: 46px; padding: 0 28px; font-size: 13.5px; font-weight: 800; border-radius: 10px; border: none; background: linear-gradient(135deg, var(--gl-green), #06b6d4); color: #ffffff; text-transform: uppercase; letter-spacing: 0.8px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.25);">
                            Create New Ticket
                        </button>
                    </div>
                </form>
            </article>
        </div>
    </section>
</main>
@endsection
