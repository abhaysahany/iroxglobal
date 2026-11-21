@extends('admin.layouts.app')

@section('title', 'View Support Tickets')

@section('content')
    <style>
        .support-view-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .support-view-icon {
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

        .support-view-heading h1 {
            margin-bottom: 4px;
        }

        .support-view-heading p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .support-chat-card {
            padding: 0;
            overflow: hidden;
        }

        .support-chat-card .panel-heading {
            margin: 0;
            padding: 18px 22px;
            border-bottom: 1px solid #eef1f6;
        }

        .support-chat-list {
            display: grid;
            gap: 16px;
            /*min-height: 260px;*/
            max-height: 520px;
            padding: 22px;
            overflow-y: auto;
            background: #f8fafc;
        }

        .support-chat-message {
            width: fit-content;
            max-width: min(78%, 680px);
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.05);
        }

        .support-chat-message.admin {
            justify-self: end;
            border-color: #dbeafe;
            background: #eff6ff;
        }

        .support-chat-message strong,
        .support-chat-message small {
            display: block;
        }

        .support-chat-message p {
            margin: 6px 0;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .support-chat-message small {
            color: var(--text-muted);
            font-size: 11px;
        }

        .support-chat-form {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 22px;
            border-top: 1px solid #eef1f6;
        }

        .support-chat-form input {
            flex: 1;
            min-width: 0;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        @media (max-width: 760px) {
            .support-view-page-header {
                align-items: flex-start;
            }

            .support-chat-message {
                max-width: 92%;
            }
        }
    </style>

    <div class="page-header support-view-page-header">
        <div class="support-view-heading">
            <span class="support-view-icon"><i class="fa-solid fa-comments"></i></span>
            <div>
                <h1>View Support Tickets</h1>
                <p>View Support Tickets</p>
            </div>
        </div>

        <div class="breadcrumb">
            <span><i class="fa-solid fa-house"></i></span>
            <span>/</span>
            <span>Admin</span>
            <span>/</span>
            <strong>View Support Tickets</strong>
        </div>
    </div>

    <section class="panel support-chat-card">
        <div class="panel-heading">
            <h2>Recent Chat</h2>
            <form action="{{ route('admin.support.close', $ticket->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Close this ticket?')" @disabled($ticket->status === 'Closed')>Close Ticket</button>
            </form>
        </div>

        <div class="support-chat-list">
            @forelse ($ticket_texts as $chat)
                <article class="support-chat-message {{ $chat->written_by === 'Admin' ? 'admin' : 'member' }}">
                    <strong>{{ $chat->written_by === 'Admin' ? (session('admin_name') ?: 'Admin') : ($ticket->member?->name ?? $ticket->memberid) }}</strong>
                    <p>{{ $chat->text }}</p>
                    <small>{{ $chat->created_at?->format('d M Y, h:i A') ?? '-' }}</small>
                </article>
            @empty
                <p>No chat messages found.</p>
            @endforelse
        </div>

        <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" class="support-chat-form">
            @csrf
            <input type="text" name="message" value="{{ old('message') }}" maxlength="1000" placeholder="Type and enter" aria-label="Reply message" required>
            <button type="submit" class="btn btn-primary" aria-label="Send reply"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </section>
@endsection
