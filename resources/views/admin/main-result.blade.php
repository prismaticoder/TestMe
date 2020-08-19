@extends('layouts.main')
@section('title') {{$current_class->class}} {{ucfirst($subject->subject_name)}} Exam Results @endsection
@section('content')

    <div class="container" id="app" data-app="true">
        <h3 class="text-center mt-5">
            {{$current_class->class}} {{$subject->subject_name}} Examination Results
        </h3>
        <hr>

        <div class="row">
            <div class="col-md-8">
            EXAM DATE: <strong>{{$selected_exam ? Carbon\Carbon::parse($selected_exam->date)->format('l, jS \o\f F Y') : (count($exams) > 0 ? Carbon\Carbon::parse($exams[0]->date)->format('l, jS \o\f F Y') . " (Most Recent)" : "NIL")}}</strong>
            </div>
        <all-results :exams="{{$exams}}" :selected_exam="{{json_encode($selected_exam)}}" :iscurrentexam="{{json_encode(!$selected_exam)}}" :subject="{{$subject}}" :class-id="{{$current_class->id}}"></all-results>
        </div>

        <table class="table table-sm mt-2 table-bordered text-center">

            <thead>
            <tr>
            <th>S/N</th>
                <th>EXAMINATION NUMBER</th>
                <th>NAME</th>
                <th>ACTUAL SCORE</th>
                <th>COMPUTED SCORE {{count($exams) > 0 ? '(/' .$exams[0]->base_score.')' : ''}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$student->code}}</td>
                    <td>{{$student->fullName}}</td>
                    <td>{{$student->score ? $student->score['actual_score'] : "-"}}</td>
                    <td>{{$student->score ? $student->score['computed_score'] : "-"}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>

@endsection

