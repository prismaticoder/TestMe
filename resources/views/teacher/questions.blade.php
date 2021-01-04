@extends('layouts.main')
@section('title') {{$class->name}} {{ucfirst($subject->name)}} Questions @endsection
@section('content')

    <div id="app">
        <v-app>
            <teacher-questions :subject-id="{{$subject->id}}" :class-id="{{$class->id}}" :exams="{{$exams}}" :default-number-of-options="4"></teacher-questions>
        </v-app>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection



