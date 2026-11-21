@extends('admin.layouts.app')

@section('title', 'ROI Income Distribution')

@section('content')
    <div class="page-header" style="margin-bottom: 10px; padding-bottom: 0;">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>ROI Income Distribution</h1>
        </div>
    </div>

    <div class="row" style="margin-top: 0;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <section class="panel settings-panel" style="max-width: 420px; margin: 0;">
                <div class="panel-heading">
                    <h2>ROI Income Distribution</h2>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.incomes.roi.distribute') }}" method="POST" class="settings-form">
                    @csrf
                    <div class="form-grid" style="display: block;">
                        <div class="form-group" style="margin-bottom: 12px; width: 100%;">
                            <label for="amount" style="display: block; margin-bottom: 6px;">Amount</label>
                            <input type="text" id="amount" name="amount" step="0.1" min="0.1" value="0.1"
                                placeholder="Enter amount" class="form-control" style="width: 100%;">
                            @error('amount')
                                <span class="error-text" style="display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 8px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary">ROI Income Dis</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
