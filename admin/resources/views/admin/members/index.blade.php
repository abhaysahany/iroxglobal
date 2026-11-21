@extends('admin.layouts.app')
@section('title', 'Manage Members')
@section('content')
    <style>
        .members-filter-form .form-grid {
            grid-template-columns: minmax(220px, 320px) auto;
            align-items: end;
            gap: 12px;
        }

        .members-filter-form .form-actions {
            justify-content: flex-start;
        }

        .members-filter-form .btn {
            min-width: 110px;
        }

        .members-table {
            min-width: 1120px;
        }

        .members-table-wrap.datatable-ready {
            overflow-x: auto;
        }

        .members-table-wrap .dataTables_wrapper {
            overflow: visible;
        }

        .members-table th:last-child,
        .members-table .members-operations-cell {
            min-width: 330px;
            width: 330px;
            background: #ffffff;
        }

        .members-table thead th:last-child {
            background: #f8fafc;
        }

        .members-table.table-striped tbody tr:nth-child(odd) .members-operations-cell {
            background: #fbfcff;
        }

        .members-operations {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .members-operations form {
            display: inline;
        }

        .members-operations .btn {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        @media (max-width: 575px) {
            .members-filter-form .form-grid {
                grid-template-columns: 1fr;
            }

            .members-filter-form .form-actions .btn {
                width: 100%;
            }

            .members-table {
                min-width: 900px;
            }

            .members-table th:last-child,
            .members-table .members-operations-cell {
                min-width: 155px;
                width: 155px;
            }

            .members-operations {
                display: grid;
                gap: 6px;
                white-space: normal;
            }

            .members-operations .btn,
            .members-operations form {
                width: 100%;
            }

            .members-operations .btn {
                min-height: 30px !important;
                justify-content: center;
                padding: 5px 7px !important;
                border-radius: 7px !important;
                font-size: 10px !important;
                line-height: 1.1;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Manage Members</h1>
        </div>
    </div>

    <section class="panel">
        <form method="GET" class="settings-form members-filter-form" style="margin-bottom: 8px;">
            @if (request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="form-grid">
                <div class="form-group">
                    <label for="state">Node State</label>
                    <select name="state" id="state">
                        <option value="all" @selected($selectedState === 'all')>All States</option>
                        <option value="active" @selected($selectedState === 'active')>Active Only</option>
                        <option value="temp" @selected($selectedState === 'temp')>Temp Only</option>
                        <option value="deactive" @selected($selectedState === 'deactive')>Deactive Only</option>
                        <option value="blocked" @selected($selectedState === 'blocked')>Blocked Only</option>
                    </select>
                </div>
                <div class="form-actions"><button class="btn btn-primary">Filter</button></div>
            </div>
        </form>

        <div class="table-wrap members-table-wrap">
            <table class="table table-bordered table-striped admin-datatable members-table" data-scroll-x="false">
                <thead>
                    <tr>

                        <th>S.No</th>
                        <th>Date</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Wallet</th>
                        <th>Joined</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ date('d-m-Y', strtotime($member['created_at'])) }}</td>
                            <td class="memberId">{{ $member->memberid }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td><span
                                    class="status {{ $member->status === 'Active' ? 'success' : 'danger' }}">{{ $member->status }}</span>
                            </td>
                            <td>{{ number_format((float) $member->wallet, 2) }}</td>
                            <td>{{ $member->created_at?->format('d M Y') ?? 'N/A' }}</td>
                            <td class="members-operations-cell">
                                <div class="members-operations">
                                    <a href="{{ url('admin/member-login') }}/{{ $member['id'] }}" class="btn btn-outline"
                                        target="_blank"
                                        style="min-height: 34px; padding: 6px 12px; border-radius: 8px; font-size: 12px; text-transform: uppercase;">
                                        <i class="fa-solid fa-user"></i> IMPERSONATE
                                    </a>

                                    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline"
                                        style="min-height: 34px; padding: 6px 12px; border-radius: 8px; font-size: 12px; text-transform: uppercase;">
                                        <i class="fa-solid fa-pen-to-square"></i> EDIT
                                    </a>
                                    @if ($member->status != 'Temp')
                                        <form action="{{ route('admin.members.status', $member) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Blocked">
                                            @if ($member->status == 'Active')
                                                <button type="submit" class="btn btn-outline"
                                                    style="min-height: 34px; padding: 6px 12px; border-radius: 8px; font-size: 12px; text-transform: uppercase;background-color:red;color:white">
                                                    <i class="fa-solid fa-ban"></i> BLOCK
                                                </button>
                                            @elseif($member->status == 'Blocked')
                                                <button type="submit" class="btn btn-outline"
                                                    style="min-height: 34px; padding: 6px 12px; border-radius: 8px; font-size: 12px; text-transform: uppercase;background-color:green;color:white">
                                                    <i class="fa-solid fa-check"></i> ACTIVE
                                                </button>
                                            @endif
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@endsection
