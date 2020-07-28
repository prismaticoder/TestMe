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
                    <a href="{{route('class-students',['class'=>strtolower($class->class)])}}" class="text-dark">
                    <h5 class="card-title">{{strtoupper($class->class)}}</h5>
                    <p class="card-text">{{$class->students()->count()}} Students</P>
                    </div>
                    <div class="card-footer">
                    <small class="text-muted"><a href="{{route('class-students',['class'=>strtolower($class->class)])}}">See Student List</a></small>
                    </div>
                </div>
            @endforeach

        </div>
    </main>
    <hr>
    <div id="app" data-app="true">
        <subjects :subjects="{{$subjects}}" :exams="{{$exams}}" :classes="{{$classes}}"></subjects>
    </div>

</div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <form>
            <div class="row">
            <div class="col-sm-5">
                <label for="">Exam Duration</label>
                </div>
                <div class="col-sm-3">
                <input type="number" class="form-control" placeholder="Hour">
                </div>
                <div class="col-sm-3">
                <input type="number" class="form-control" placeholder="Minutes">
                </div>
            </div>
            <div class="form-group row mt-3">
                <label for="colFormLabel" class="col-sm-5 col-form-label">Total Allotable Marks</label>
                <div class="col-sm-4">
                <input type="number" class="form-control" id="colFormLabel" placeholder="score">
                </div>
            </div>
            </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Start Exam</button>
            </div>
        </div>
        </div>
        <script src="{{asset('/js/app.js')}}"></script>
@endsection
