@extends('layouts.main')
@section('title', 'Main Dashboard')
@section('content')

    <div id="app" data-app="true">
        <add-question :subject="{{$subject->id}}" :class-id="{{$class_id}}" :exams="{{$exams}}"></add-question>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection



