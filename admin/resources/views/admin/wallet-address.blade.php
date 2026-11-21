@extends('admin.layouts.app')

@section('title', 'Member Wallet Address')

@section('content')
    <style>
        .member-wallet-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border);
            background: #ffffff;
        }

        .member-wallet-form {
            display: inline-flex;
        }

        .member-wallet-input {
            min-height: 38px;
            padding: 9px 11px;
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
            width: 100%;
            min-width: 250px;
        }

        .member-wallet-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(132, 90, 223, 0.12);
        }

        .member-wallet-form .btn {
            min-height: 38px;
            padding: 8px 14px;
            white-space: nowrap;
        }
    </style>

    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Member Wallet Address</h1>
        </div>
    </div>

    <section class="panel">
        @if (session()->has('update'))
            <div class="alert alert-success">{{ session('update') }}</div>
        @endif

        <div class="panel-heading">
            <h2>Member Wallet Address Details & Updation</h2>
        </div>

        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Avatar</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Wallet Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $list)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img
                                    src="{{ $list->profile_image ? asset('uploads/' . $list->profile_image) : asset('uploads/avatar.jpg') }}"
                                    class="member-wallet-avatar"
                                    alt="{{ $list->name }} avatar"
                                >
                            </td>
                            <td>{{ $list->memberid }}</td>
                            <td>{{ $list->name }}</td>
                            <td>
                                <input type="text" name="address" form="wallet-form-{{ $list->id }}" class="member-wallet-input" value="{{ $list->wallet_address }}" placeholder="Enter Wallet Address">
                            </td>
                            <td>
                                <form id="wallet-form-{{ $list->id }}" action="{{ route('admin.updateWallet') }}" method="post" class="member-wallet-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $list->id }}">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 18px;">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
