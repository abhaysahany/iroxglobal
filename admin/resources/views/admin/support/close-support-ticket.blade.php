@extends('admin.layouts.app')

@section('title', 'Close Support Tickets')

@section('content')
    @include('admin.support.partials.ticket-list', ['pageTitle' => 'Close Support Tickets'])
@endsection
