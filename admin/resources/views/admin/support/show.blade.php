@extends('admin.layouts.app')

@section('title', 'Support Ticket')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>{{ $support->subject }}</h1>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline">Back</a>
    </div>

    <section class="panel settings-panel">
        <p><strong>Ticket ID:</strong> {{ $support->ticket_id }}</p>
        <p><strong>Memberid:</strong> {{ $support->memberid }}</p>
        <p><strong>Member:</strong> {{ $support->member?->name ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $support->status }}</p>
        <p><strong>Support Status:</strong> {{ $support->support_status }}</p>
        <p><strong>Remark:</strong></p>
        <p>{{ $support->remark ?: 'No remark available.' }}</p>
    </section>
@endsection
