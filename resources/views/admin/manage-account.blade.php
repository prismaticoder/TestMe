{{-- This is the view for the admin main page. It contains the list of all subjects with links to host exam, add questions, view results, it also has a navbar with links to the students page --}}
@extends('layouts.main')
@section('title', 'Manage Account')
@section('pageHeader', 'Manage Account')
@section('content')

    <div id="app">
        <v-app>
            <manage-account></manage-account>
        </v-app>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
