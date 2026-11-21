@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    @php
        $formatDate = function ($value) {
            if (! $value) {
                return 'N/A';
            }

            try {
                return $value instanceof \Carbon\CarbonInterface
                    ? $value->format('d M Y')
                    : \Illuminate\Support\Carbon::parse($value)->format('d M Y');
            } catch (\Throwable $e) {
                return 'N/A';
            }
        };
    @endphp

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>{{ $title }}</h1>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table table-bordered table-striped admin-datatable">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Ticket ID</th>
                    <th>Member ID</th>
                    <th>Member Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>{{ $type === 'closed' ? 'Closed Date' : 'Created Date' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    @php
                        $serial = (($tickets->currentPage() - 1) * $tickets->perPage()) + $loop->iteration;
                    @endphp

                    <tr>
                        <td>{{ $serial }}</td>
                        <td>{{ $ticket->id ?? 'N/A' }}</td>
                        <td>{{ $ticket->member?->member_id ?? 'N/A' }}</td>
                        <td>{{ $ticket->member?->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->member?->email ?? 'N/A' }}</td>
                        <td>{{ $ticket->subject ?? 'N/A' }}</td>
                        <td>{{ $ticket->priority ?? 'N/A' }}</td>
                        <td>{{ $ticket->status ?? 'N/A' }}</td>
                        <td>{{ $formatDate($type === 'closed' ? ($ticket->updated_at ?? null) : ($ticket->created_at ?? null)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 18px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tickets->links() }}
@endsection
