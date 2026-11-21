@extends('admin.layouts.app')

@section('title', 'Support')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Support Tickets</h1>
        </div>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead><tr><th>Subject</th><th>Member</th><th>Priority</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ $ticket->member?->name ?? 'Guest' }}</td>
                            <td><span class="status {{ $ticket->priority === 'High' ? 'danger' : ($ticket->priority === 'Low' ? 'success' : 'warning') }}">{{ $ticket->priority }}</span></td>
                            <td><span class="status {{ $ticket->status === 'Closed' ? 'success' : 'warning' }}">{{ $ticket->status }}</span></td>
                            <td>{{ $ticket->created_at->format('d M Y') }}</td>
                            <td><a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-outline">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No support tickets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tickets->links() }}
    </section>
@endsection
