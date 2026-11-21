@extends('admin.layouts.app')

@section('title', 'Deduction')

@section('content')
    <div class="page-header" style="margin-bottom: 10px; padding-bottom: 0;">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Deduction</h1>
        </div>
    </div>

    <div class="row" style="margin-top: 0;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <section class="panel settings-panel" style="max-width: 420px; margin: 0;">
                <div class="panel-heading">
                    <h2>Deduction Form</h2>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.dedtion') }}" method="POST" class="settings-form">
                    @csrf
                    <div class="form-grid" style="display: block;">
                        {{-- <div class="form-group" style="margin-bottom: 12px; width: 100%;">
                            <label for="amount" style="display: block; margin-bottom: 6px;">Amount</label>
                            <input type="number" id="amount" name="amount" step="0.1" min="0.1" value="0.1"
                                placeholder="Enter amount" class="form-control" style="width: 100%;">
                            @error('amount')
                                <span class="error-text" style="display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group" style="margin-bottom: 12px; width: 100%;">
                            <label>Withdrawal Deduction</label>
                            <input type="text" name="rate" class="form-control" value="{{ $data['rate'] }}"
                                required />
                            @error('rate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-bottom: 12px; width: 100%;">
                            <label>Wallet To P2P Deduction</label>
                            <input type="text" name="walletToP2P" class="form-control" value="{{ $data['walletToP2P'] }}"
                                required />
                            @error('walletToP2P')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 8px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
