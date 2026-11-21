@extends('admin.layouts.app')

@section('title', 'Addon Data')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Addon Data</h1>
        </div>
    </div>

    @if (session('successMsg'))
        <div class="alert alert-success">{{ session('successMsg') }}</div>
    @endif
    @if (session('failedMsg'))
        <div class="alert alert-danger">{{ session('failedMsg') }}</div>
    @endif

    <section class="panel settings-panel">
        <div class="panel-heading"><h2>Add Member Data</h2></div>
        <form action="{{ route('admin.AddonDataInsert') }}" method="POST" class="settings-form">
            @csrf
            @include('admin.partials.addon-data-form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </section>

    <section class="panel" style="margin-top: 20px;">
        <div class="panel-heading"><h2>Saved Addon Data</h2></div>
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>My Direct</th>
                        <th>Total Team</th>
                        <th>Global Members</th>
                        <th>Total Withdrawal</th>
                        <th>P2P Wallet</th>
                        <th>Rank Income</th>
                        <th>Daily Income</th>
                        <th>Referral Income</th>
                        <th>Global Rec Income</th>
                        <th>Global Rec Star Income</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fakeData as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->memberid }}</td>
                            <td>{{ $item->my_direct }}</td>
                            <td>{{ $item->total_team }}</td>
                            <td>{{ $item->global_members }}</td>
                            <td>{{ $item->total_withdrawal }}</td>
                            <td>{{ $item->p2p_wallet }}</td>
                            <td>{{ $item->rank_income }}</td>
                            <td>{{ $item->daily_income }}</td>
                            <td>{{ $item->referral_income }}</td>
                            <td>{{ $item->global_rec_income }}</td>
                            <td>{{ $item->global_rec_star_income }}</td>
                            <td><a class="btn btn-outline" href="{{ url('/admin/addon-data/edit/'.$item->id) }}">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="13" style="text-align:center; padding:18px;">No addon data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
