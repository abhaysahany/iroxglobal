@extends('admin.layouts.app')

@section('title', 'Edit Addon Data')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Edit Addon Data</h1>
        </div>
        <a href="{{ url('/admin/addon-data') }}" class="btn btn-outline">Back</a>
    </div>

    @if (session('successMsg'))
        <div class="alert alert-success">{{ session('successMsg') }}</div>
    @endif
    @if (session('failedMsg'))
        <div class="alert alert-danger">{{ session('failedMsg') }}</div>
    @endif

    <section class="panel settings-panel">
        <div class="panel-heading"><h2>Update Member Data</h2></div>
        <form action="{{ route('admin.AddonDataUpdate') }}" method="POST" class="settings-form">
            @csrf
            @include('admin.partials.addon-data-form', ['addon' => $data])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </section>
@endsection
