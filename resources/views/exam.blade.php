{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
<script src="{{asset('/js/jquery.js')}}"></script>
<script src="{{asset('/js/functions.js')}}"></script>
    <title>Student Examination</title>
</head>
<body>
    <div id="question">
            This is a question<br>
    </div>
    <ul>
                        <li> A: <input type="radio" name="options" id=""> <span class=options></span></li>
                        <li> B: <input type="radio" name="options" id=""> <span class=options></span></li>
                        <li> C: <input type="radio" name="options" id=""> <span class=options></span></li>
                        <li> D: <input type="radio" name="options" id=""> <span class=options></span></li>
    </ul>
                <button class="nxtButton" id="1">NEXT</button>

        <a href="{{route('logout')}}">Logout</a>

        <form action="" method="post" id="reloadForm">
            <input type="hidden" name="subject" id="subjectH">
            <input type="hidden" name="class" id="classH">
            <input type="hidden" name="question_id" id="questionH">
        </form>


</body>
</html>
