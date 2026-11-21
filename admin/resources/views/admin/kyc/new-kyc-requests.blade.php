@extends('admin.layouts.app')

@section('title', 'New KYC Requests')

@section('content')
    <div class="page-header" style="margin-bottom: 20px; padding-bottom: 0;">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>New KYC Requests</h1>
        </div>
    </div>

    @if (session()->has('kycMesg'))
        <div class="alert alert-success" style="color: green; font-weight: bold; margin-bottom: 20px;">
            {{ session('kycMesg') }}
        </div>
    @endif

    <section class="panel">
        <div class="panel-heading">
            <h2>Pending KYC Submissions</h2>
        </div>
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">S.No</th>
                        <th style="width: 80px; text-align: center;">Avatar</th>
                        <th>Member Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Sponsor Id</th>
                        <th>Status</th>
                        <th style="width: 150px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse ($data as $list)
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">{{ $i }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                @if ($list['profile_image'] == '')
                                    <img src="{{ asset('uploads/avatar.jpg') }}"
                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
                                        alt="Avatar">
                                @else
                                    <img src="{{ config('detailsApp.app_url') . '/uploads/' . $list['profile_image'] }}"
                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
                                        alt="Avatar">
                                @endif
                            </td>
                            <td style="vertical-align: middle;"><strong>{{ $list['memberid'] }}</strong></td>
                            <td style="vertical-align: middle;">{{ $list['name'] }}</td>
                            <td style="vertical-align: middle;">{{ $list['email'] }}</td>
                            <td style="vertical-align: middle;">{{ $list['mobile'] }}</td>
                            <td style="vertical-align: middle;">{{ $list['sponsorid'] }}</td>
                            <td style="vertical-align: middle;">
                                <span class="status {{ $list['status'] == 'Active' ? 'success' : 'danger' }}">
                                    {{ $list['status'] }}
                                </span>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <a class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;"
                                    href="{{ url('admin/KYC/view/' . $list['id']) }}">
                                    <i class="fa-solid fa-eye" style="margin-right: 4px;"></i> View KYC
                                </a>
                            </td>
                        </tr>
                        @php $i++; @endphp
                    @empty
                        <tr>
                            <td colspan="9"
                                style="text-align: center; padding: 20px; font-style: italic; color: #64748b;">No pending
                                KYC requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
