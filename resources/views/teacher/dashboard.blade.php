{{-- This is the view for the admin main page. It contains the list of all subjects with links to host exam, add questions, view results, it also has a navbar with links to the students page --}}
@extends('layouts.main')
@section('title', 'Main Dashboard')
@section('pageHeader', 'Main Dashboard')
@section('content')
@can('superAdminGate')
    <main role="main">
        <h4>Students</h4>
        <hr>
        <div class="row">

            @foreach ($classes as $class)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                    <a href="/admin/students#{{$class->trimmed_name}}" class="text-dark">
                            <h5 class="card-title">{{strtoupper($class->name)}}</h5>
                            <p class="card-text">{{$class->students()->withTrashed()->count()}} Students</p>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </main>
    <hr>
@endcan

    <div id="app" data-app="true">
        <dashboard :subjects="{{$subjects}}" :started-exams="{{$startedExams}}" :pending-exams-for-today="{{$pendingExamsForToday}}" :classes="{{$classes}}"></dashboard>
    </div>


    <script src="{{asset('/js/app.js')}}"></script>
@endsection
