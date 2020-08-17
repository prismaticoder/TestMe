@extends('layouts.main')
@section('title', 'All Students')
@section('pageHeader', 'All Students')
@section('content')

    <div class="container" id="app" data-app="true">
        {{-- <v-app> --}}
            <class-students :classes="{{$classes}}"></class-students>
        {{-- </v-app> --}}
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
