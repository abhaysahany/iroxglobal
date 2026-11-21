@extends('admin.layouts.app')
@section('title', 'Global Pool Members')
@section('content')
    <style>
        .member-security-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border);
            background: #ffffff;
        }

        .member-security-form {
            display: inline-flex;
        }

        .member-security-input {
            min-height: 38px;
            padding: 9px 11px;
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
        }

        .member-security-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(132, 90, 223, 0.12);
        }

        .member-security-form .btn {
            min-height: 38px;
            padding: 8px 14px;
            white-space: nowrap;
        }
    </style>

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Global Pool Members</h1>
        </div>
    </div>

    <section class="panel">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="panel-heading">
            <h2>All Global Pool Members</h2>
        </div>

        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Member ID</th>
                        <th>Pool No</th>
                        <th>Member 1</th>
                        <th>Member 2</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $list)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $list->created_at?->format('d M Y') ?? 'N/A' }}</td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 700;">
                                    {{ $list['memberid'] }}
                                </div>
                                <div style="font-size: 12px; margin-top: 3px; opacity: 0.8;">
                                    {{ getname($list['memberid']) }}
                                </div>
                            </td>
                            <td>{{ $list->pool_no }}</td>
                            <!--<td> ({{ $list->upline_1 }}) {{ getName($list->upline_1) }}</td>-->
                            <!--<td> ({{ $list->upline_2 }}) {{ getName($list->upline_2) }}</td>-->
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 700;">
                                    {{ $list['upline_1'] }}
                                </div>
                                <div style="font-size: 12px; margin-top: 3px; opacity: 0.8;">
                                    {{ getname($list['upline_1']) }}
                                </div>
                            </td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 700;">
                                    {{ $list['upline_2'] }}
                                </div>
                                <div style="font-size: 12px; margin-top: 3px; opacity: 0.8;">
                                    {{ getname($list['upline_2']) }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 18px;">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
