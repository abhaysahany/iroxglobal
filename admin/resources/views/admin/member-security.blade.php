@extends('admin.layouts.app')

@section('title', 'Member Account Security')

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
            <h1>Member Account Security</h1>
        </div>
    </div>

    <section class="panel">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="panel-heading">
            <h2>All Member Account Security</h2>
        </div>

        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <!--<th>Avatar</th>-->
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Password</th>
                        <th>Security Pin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $list)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <!--<td>-->
                            <!--    <img-->
                            <!--        src="{{ $list->profile_image ? config('detailsApp.app_url').'/uploads/'.$list->profile_image : asset('uploads/avatar.jpg') }}"-->
                            <!--        class="member-security-avatar"-->
                            <!--        alt="{{ $list->name }} avatar"-->
                            <!--    >-->
                            <!--</td>-->
                            <td>{{ $list->memberid }}</td>
                            <td>{{ $list->name }}</td>
                            <td>
                                <input type="password" name="password" form="security-form-{{ $list->id }}" class="member-security-input" placeholder="Enter Password" autocomplete="new-password" >
                            </td>
                            <td>
                                <input type="password" name="txnpassword" form="security-form-{{ $list->id }}" class="member-security-input" placeholder="Enter Security Pin" autocomplete="new-password" >
                            </td>
                            <td>
                                <form id="security-form-{{ $list->id }}" action="{{ route('admin.member-security.update') }}" method="POST" class="member-security-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $list->id }}">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 18px;">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
