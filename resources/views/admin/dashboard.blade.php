{{-- This is the view for the admin main page. It contains the list of all subjects with links to host exam, add questions, view results, it also has a navbar with links to the students page --}}
@extends('layouts.main')
@section('title', 'Main Dashboard')
@section('pageHeader', 'Main Dashboard')
@section('content')
    <main role="main">
        <h4>Students</h4>
        <hr>
        <div class="card-deck">

            @foreach ($classes as $class)
                <div class="card">
                    <div class="card-body">
                    <a href="/admin/students#{{strtolower($class->class)}}" class="text-dark">
                            <h5 class="card-title">{{strtoupper($class->class)}}</h5>
                            <p class="card-text">{{$class->students()->withTrashed()->count()}} Students</p>
                        </a>
                    </div>

                </div>
            @endforeach

        </div>
    </main>
    <hr>
    <div id="app" data-app="true">
        <subjects :subjects="{{$subjects}}" :exams="{{$exams}}" :classes="{{$classes}}"></subjects>
    </div>


    <script src="{{asset('/js/app.js')}}"></script>
@endsection
