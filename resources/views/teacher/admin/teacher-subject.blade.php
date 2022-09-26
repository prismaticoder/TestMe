@extends('layouts.main')
@if ($type == 'teachers')
    @section('title', 'Teachers')
    @section('pageHeader', 'Maintain Teacher User List')
    @section('content')

    <div id="app">
        <v-app>
            <teachers :allsubjects="{{$subjects}}" :allteachers="{{$teachers}}" :allclasses="{{$classes}}"></teachers>
        </v-app>
    </div>
@else

    @section('title', 'Subjects')
    @section('pageHeader', 'Add/Update Subjects')
    @section('content')

    <div id="app">
        <v-app>
            <subjects :allsubjects="{{$subjects}}" :allclasses="{{$classes}}"></subjects>
        </v-app>
    </div>

@endif

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
