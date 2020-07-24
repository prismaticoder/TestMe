@extends('layouts.main')
@section('title', 'Results')
@section('content')
<div class="container">



    <h1 style="margin-top:120px;">Exam Results Page</h1>

    <main class="card-columns mt-3">
        @foreach ($subjects as $subject)
            <div class="card">
                <div class="card-body">
                    <p class="card-text">{{strtoupper($subject->alias)}}</p>
                </div>
                <div class="card-footer">
                    Class results >
                    @foreach ($classes as $class)
                    <a href="{{route('singleresult',['subject'=>$subject->alias,'class_id'=>$class->id])}}" class="btn btn-secondary">JSS {{$class->id}}</a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </main>

</div>

@endsection
