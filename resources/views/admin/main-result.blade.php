@extends('layouts.main')
@section('title') {{$current_class->class}} {{ucfirst($subject->subject_name)}} Exam Results @endsection
@section('content')

    <div class="container">
        <h3 class="text-center mt-5">
            {{$current_class->class}} {{$subject->subject_name}} Examination Results
        </h3>
        <hr>

          <table class="table table-sm mt-5">

              <thead>
                <tr>
                <th>S/N</th>
                  <th>Registration Number</th>
                  <th>Surname</th>
                  <th>First name</th>
                  <th>Actual Score</th>
                  <th>Score (/50)</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($students as $student)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$student->code}}</td>
                        <td>{{$student->lastname}}</td>
                        <td>{{$student->firstname}}</td>
                        <td>{{$student->score ? $student->score['actual_score'] : "-"}}</td>
                        <td>{{$student->score ? $student->score['computed_score'] : "-"}}</td>
                    </tr>
                  @endforeach

              </tbody>
            </table>
    </div>

@endsection

