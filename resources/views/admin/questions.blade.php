@extends('layouts.main')
@section('title', 'Main Dashboard')
@section('content')

    <div id="app" data-app="true">
        <add-question :questions="{{$questions}}" :subject="{{$subject->id}}" :class-id="{{$class_id}}" :params="{{$mark}}"></add-question>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection



