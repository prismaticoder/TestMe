@extends('layouts.main')
@section('title', 'All Students')
@section('pageHeader', 'All Students')
@section('content')

    <div class="container" id="app" data-app="true">
        <students :allclasses="{{$classes}}" :isadmin="{{json_encode(auth()->user()->can('superAdminGate'))}}"></students>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
