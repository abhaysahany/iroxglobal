@extends('admin.layouts.app')

@section('title', 'Activation')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Activation</h1>
        </div>
    </div>

    <section class="panel settings-panel">
        <div class="panel-heading"><h2>Activate Member</h2></div>
        <form action="{{ route('admin.activations.activate') }}" method="POST" class="settings-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Member</label>
                    <select name="member_id">
                        <option value="">Select Member</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->member_id }} - {{ $member->name }}</option>
                        @endforeach
                    </select>
                    @error('member_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Package Amount</label>
                    <input type="number" step="0.01" name="package_amount" value="{{ old('package_amount') }}">
                    @error('package_amount') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-actions"><button class="btn btn-primary">Activate</button></div>
        </form>
    </section>

    <section class="panel">
        <div class="panel-heading"><h2>Activation History</h2></div>
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Activated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activations as $activation)
                        <tr>
                            <td>{{ $activation->member?->name ?? '-' }}</td>
                            <td>{{ number_format($activation->package_amount, 2) }}</td>
                            <td><span class="status success">{{ $activation->status }}</span></td>
                            <td>{{ $activation->activated_at?->format('d M Y') ?? '-' }}</td>
                            <td>
                                @if ($activation->member)
                                    <form action="{{ route('admin.activations.deactivate', $activation->member) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-outline">Deactivate</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No activation history found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $activations->links() }}
    </section>
@endsection
