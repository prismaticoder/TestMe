@extends('layouts.main')
@section('title', 'Teachers')
@section('pageHeader', 'Administrative Section')
@section('content')


    <div id="app" data-app="true">
        <admin-section :allsubjects="{{$subjects}}" :allteachers="{{$teachers}}" :allclasses="{{$classes}}"></admin-section>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
