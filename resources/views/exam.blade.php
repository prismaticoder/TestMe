{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
<title>Oasis CBT | {{$subject->subject_name}} Examination</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <style>
        html,
        body {
        border: border-box;
        }

        body {
        background-color: #f5f5f5;

        }
        .question-body {
           padding: 130px 100px 50px;
           height: 100vh;
            overflow-y: scroll;
        }
        .question-body input {
            margin-right: 15px;
        }

        .radioBtn {
            width: 15px;
            height: 15px;
        }
        .sidebar {
            padding: 70px 0 20px;
            height: 100vh;
            overflow-y: scroll;
            background-color: #fff;
        }

        .radios:hover {
            border:solid #204d74 1px;
        }
    </style>

</head>
<body class="container-fluid">
    <div id="app" data-app="true">
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
<a class="navbar-brand navbar-text" style="color:white">{{env('SCHOOL_NAME')}}</a>
<div class="order-5">
    <Timer :hours="{{$hours}}" :minutes="{{$minutes}}"></Timer>
</div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
            <a class="nav-link"><span>{{strtoupper($subject->subject_name)}}</span> JSS<span class="class">{{$user->class_id}}</span> <span class="sr-only">(current)</span></a>
            </li>
            <span style="display:none" class="subject">{{strtoupper($subject->alias)}}</span>
            <li class="nav-item">
            <a class="nav-link">{{$user->getFullName()}}</a>
            </li>
            <li class="nav-item">
            <a class="nav-link">Exam No: {{$user->code}}</a>
            </li>
            </ul>
            <input type="hidden" name="" class="reloader" value="0">
        </div>
    </nav>

    <div>
        <Questions :questions="{{$questions}}" :hours="{{$hours}}" :minutes="{{$minutes}}"></Questions>
        {{-- <Timer :hours="{{$hours}}" :minutes="{{$minutes}}"></Timer> --}}
    </div>
    {{-- <script src="{{asset('/js/functions.js')}}"></script> --}}
</div>
<script src="{{asset('/js/app.js')}}"></script>

<script src="{{asset('/js/jquery.js')}}"></script>
</body>
</html>
