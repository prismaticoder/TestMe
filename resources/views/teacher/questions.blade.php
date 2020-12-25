@extends('layouts.main')
@section('title') {{$currentClass->name}} {{ucfirst($subject->name)}} Questions @endsection
@section('content')

    <div id="app">
        <v-app>
            <teacher-questions :subject-id="{{$subject->id}}" :class-id="{{$currentClass->id}}" :exams="{{$exams}}"></teacher-questions>
        </v-app>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection



