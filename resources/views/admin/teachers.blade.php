@extends('layouts.main')
@section('title', 'Teachers')
@section('pageHeader', 'Maintain Teacher User List')
@section('content')

    <div id="app">
        <v-app>
            <teachers :allsubjects="{{$subjects}}" :allteachers="{{$teachers}}" :allclasses="{{$classes}}"></teachers>
        </v-app>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
