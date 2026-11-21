<style>
    .support-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .support-heading-icon {
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

    .support-heading h1 {
        margin-bottom: 4px;
    }

    .support-heading p {
        margin: 0;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .support-list-card {
        padding: 0;
        overflow: hidden;
    }

    .support-list-card .panel-heading {
        margin: 0;
        padding: 18px 22px;
        border-bottom: 1px solid #eef1f6;
    }

    .support-list-card .table-wrap {
        padding: 20px 22px 22px;
    }

    .support-list-card thead th {
        background: #f4f6f9;
    }

    @media (max-width: 760px) {
        .support-page-header {
            align-items: flex-start;
        }
    }
</style>

<div class="page-header support-page-header">
    <div class="support-heading">
        <span class="support-heading-icon"><i class="fa-solid fa-headset"></i></span>
        <div>
            <h1>{{ $pageTitle }}</h1>
            <p>{{ $pageTitle }}</p>
        </div>
    </div>

    <div class="breadcrumb">
        <span><i class="fa-solid fa-house"></i></span>
        <span>/</span>
        <span>Admin</span>
        <span>/</span>
        <strong>{{ $pageTitle }}</strong>
    </div>
</div>

<section class="panel support-list-card">
    <div class="panel-heading">
        <h2>{{ $pageTitle }}</h2>
    </div>

    <div class="table-wrap">
        <table class="table table-bordered table-striped admin-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Request Date</th>
                    <th>Ticket ID</th>
                    <th>Memberid</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($support as $ticket)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ticket->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                        <td>{{ $ticket->ticket_id }}</td>
                        <td>{{ $ticket->memberid }}</td>
                        <td>{{ $ticket->member?->name ?? '-' }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>
                            <a href="{{ route('admin.support.view', $ticket->id) }}" class="btn btn-primary" style="min-height: 34px; padding: 6px 12px;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No support tickets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
