@extends('admin.layouts.app')

@section('title', 'Open Support Tickets')

@section('content')
    @include('admin.support.partials.ticket-list', ['pageTitle' => 'Open Support Tickets'])
@endsection
