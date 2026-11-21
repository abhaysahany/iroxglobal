@extends('member.layouts.app')

@section('title', 'Ticket Details - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'Ticket Details')

@section('content')
<main class="member-page dashboard-page support-page">
    <div class="dashboard-backdrop"></div>
    @include('member.layouts.sidebar')

    <section class="dashboard-shell">
        @include('member.layouts.header')

        <div class="dashboard-content">
            <!-- BREADCRUMB -->
            <!--<div class="support-breadcrumb" style="margin-bottom: 20px; font-size: 13px; font-weight: 700; color: var(--gl-muted);">-->
            <!--    Demo Wealth Portal / <a href="{{ route('member.support.tickets') }}" style="color: var(--gl-muted); text-decoration: none;">Support Tickets</a> / Ticket Details-->
            <!--</div>-->

            <!-- TICKET INFO HEADER CARD -->
            <article class="support-hero glass-surface" style="border-radius: 16px; border: 1px solid var(--gl-border); background: linear-gradient(135deg, rgba(5, 15, 8, 0.65), rgba(73, 133, 36, 0.1)); box-shadow: var(--gl-shadow); backdrop-filter: blur(10px); padding: 30px; margin-bottom: 24px;">
                <div class="support-hero-content" style="display: flex; justify-content: space-between; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div class="support-hero-left" style="flex: 1 1 450px;">
                        <span class="support-pill" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255, 255, 255, 0.08); border: 1px solid var(--gl-border); color: var(--gl-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Ticket ID: {{ $support->ticket_id ?? $support->id ?? '-' }}</span>
                        <h1 class="support-title" style="font-size: 28px; font-weight: 800; color: var(--gl-gold); margin: 0 0 12px;">{{ $support->subject ?? 'No Subject' }}</h1>
                        <p class="support-desc" style="font-size: 14px; line-height: 1.5; color: var(--gl-text); margin: 0 0 20px;">Created on: {{ $support->created_at ?? '-' }}</p>
                        
                        <div style="display: flex; gap: 12px; align-items: center;">
                            @php
                                $statusStr = $support->status ?? 'Open';
                                $status = strtolower(str_replace(' ', '-', $statusStr));
                            @endphp
                            <span class="support-status support-status-{{ $status }}">
                                {{ $statusStr }}
                            </span>
                            <a href="{{ route('member.support.tickets') }}" class="pill-button" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; min-height: 32px; padding: 0 16px; font-size: 12px; font-weight: 700; border-radius: 8px;">
                                <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- CHAT BUBBLES CONVERSATION BOX -->
            <article class="support-table-card glass-surface" style="padding: 30px; border-radius: 16px; border: 1px solid var(--gl-border); background: var(--gl-panel); margin-bottom: 24px;">
                <h2 style="font-size: 18px; margin: 0 0 20px; color: var(--gl-gold); font-weight: 800; border-bottom: 1px solid var(--gl-border); padding-bottom: 12px;">
                    <i class="fa-solid fa-comments" style="margin-right: 8px;"></i> Ticket Conversation
                </h2>

                <div class="support-chat-body" style="display: flex; flex-direction: column; gap: 16px; max-height: 480px; overflow-y: auto; padding: 10px; margin-bottom: 20px; border-radius: 10px; background: rgba(5,15,8,0.2); border: 1px solid var(--gl-border); box-shadow: inset 0 2px 8px var(--gl-shadow);">
                    @forelse ($tdata ?? [] as $chat)
                        @php
                            $isMember = strtolower($chat->written_by ?? '') === 'member';
                        @endphp
                        <div class="support-chat-bubble-wrap" style="display: flex; flex-direction: column; align-items: {{ $isMember ? 'flex-end' : 'flex-start' }}; width: 100%;">
                            <div class="support-chat-bubble {{ $isMember ? 'member' : 'admin' }}" 
                                 style="max-width: 75%; padding: 14px 18px; border-radius: 14px; border: 1px solid var(--gl-border); 
                                        font-size: 13.5px; line-height: 1.5; font-weight: 600; color: var(--gl-text);
                                        background: {{ $isMember ? 'rgba(73, 133, 36, 0.15)' : 'rgba(212, 175, 55, 0.1)' }};
                                        border-color: {{ $isMember ? 'var(--gl-green)' : 'var(--gl-gold)' }};">
                                <p style="margin: 0; white-space: pre-wrap;">{{ $chat->text ?? '' }}</p>
                            </div>
                            <small style="font-size: 11px; color: var(--gl-muted); font-weight: 700; margin-top: 4px; padding: 0 4px;">
                                {{ $isMember ? 'You' : 'Support Agent' }} • {{ $chat->created_at ?? '-' }}
                            </small>
                        </div>
                    @empty
                        <div style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 40px 20px; font-weight: 600;">
                            No messages in this ticket conversation yet.
                        </div>
                    @endforelse
                </div>

                <!-- ALERTS FOR REPLY SUCCESS/ERRORS -->
                @if (session('createMsg'))
                    <div class="support-alert alert-success" style="padding: 12px 16px; border-radius: 8px; border: 1px solid var(--gl-green); background: rgba(73, 133, 36, 0.1); color: var(--gl-green); font-size: 13.5px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('createMsg') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="support-error" style="padding: 12px 16px; border-radius: 8px; border: 1px solid var(--gl-danger); background: rgba(220, 53, 69, 0.05); color: var(--gl-danger); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 16px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- REPLY FORM -->
                @if (strtolower($support->status ?? '') !== 'closed')
                    <form action="{{ route('member.support.tickets.reply') }}" method="POST" class="support-form" style="display: flex; flex-direction: column; gap: 16px;">
                        @csrf
                        <input type="hidden" name="ticketid" value="{{ $support->ticket_id ?? $support->id ?? '' }}">

                        <div class="support-field" style="display: flex; flex-direction: column; gap: 8px;">
                            <label for="reply" class="support-label" style="font-size: 12.5px; font-weight: 800; color: var(--gl-gold); text-transform: uppercase; letter-spacing: 0.5px;">Type Your Message</label>
                            <textarea name="reply" id="reply" rows="4" class="support-textarea" placeholder="Type your reply message here..." required style="padding: 14px 16px; border-radius: 10px; border: 1px solid var(--gl-border); background: rgba(5, 15, 8, 0.45); color: var(--gl-text); font-weight: 600; font-size: 14px; outline: none; transition: border-color 0.2s, box-shadow 0.2s; resize: vertical; box-shadow: inset 0 2px 4px rgba(0,0,0,0.15);"></textarea>
                        </div>

                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="support-btn" style="min-height: 40px; padding: 0 24px; font-size: 12.5px; font-weight: 800; border-radius: 8px; border: none; background: linear-gradient(135deg, var(--gl-green), #06b6d4); color: #ffffff; text-transform: uppercase; letter-spacing: 0.8px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.25);">
                                <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Send Reply
                            </button>
                        </div>
                    </form>
                @else
                    <div class="support-alert" style="padding: 12px 16px; border-radius: 8px; border: 1px solid var(--gl-border); background: rgba(255,255,255,0.05); color: var(--gl-muted); font-size: 13.5px; font-weight: 700; text-align: center;">
                        <i class="fa-solid fa-lock" style="margin-right: 6px;"></i> This ticket is closed. You can no longer reply to it.
                    </div>
                @endif
            </article>
        </div>
    </section>
</main>
@endsection
