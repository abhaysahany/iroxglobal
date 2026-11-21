@extends('admin.layouts.app')

@section('title', 'New Support Tickets')

@section('content')
    @include('admin.support.partials.ticket-list', ['pageTitle' => 'New Support Tickets'])
@endsection
