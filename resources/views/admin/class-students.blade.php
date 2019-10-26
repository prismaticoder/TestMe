{{-- Page showing all students of each class with links to add, edit or delete the students --}}
<table>
    <tr><th>Firstname</th><th>Lastname</th><th>Registration Number</th></tr>
@foreach ($students as $student )
    <tr><td>{{$student->firstname}}</td><td>{{$student->lastname}}</td><td>{{$student->code}}</td></tr>
@endforeach
<table>
