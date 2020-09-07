{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="icon" href="{{asset('/img/logo.png')}}">


    <title>{{config('app.name')}} | {{config('app.schoolAlias')}} - {{ucfirst($subject->subject_name). ' ' . Auth::user()->class->class}} Examination</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <style>
        /* html,
        body {
        border: border-box;
        } */

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

        .radios.disabled:hover {
            cursor: not-allowed
        }
        .radios:hover {
            border:solid #204d74 1px;
        }

        ul .nav-link:hover {
            border-bottom: solid #e67d23 2px
        }

        ul>.nav-item>.active {
            border-bottom: solid #e67d23 2px
        }

        ul .nav-link {
            padding: 8px;
            margin-left: 12px;
        }
    </style>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
            <a href="">
                <img src="{{asset('/img/logo.png')}}" class="mr-2" height="45" width="45">
            </a>
            <a class="navbar-brand" href="">
                {{config('app.name')}} | {{config('app.schoolAlias')}} {{strtoupper($subject->subject_name) . ' ' . Auth::user()->class->class}} EXAMINATION
            </a>
            <div class="order-5">
                <Timer :hours="{{$hours}}" :minutes="{{$minutes}}"></Timer>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mx-auto col-md-5">
                    <li class="nav-item">
                        <a class="nav-link active" href="">{{Auth::user()->fullName}} (Exam No {{Auth::user()->code}})</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div>
            {{-- made it class-id(kebab-case) because HTML converts it to lowercase automatically --}}
            <v-app>
            <Questions :questions="{{$questions}}" :hours="{{$hours}}" :minutes="{{$minutes}}" :mainsubject="{{$subject}}" :subject="{{$subject->id}}" :class-id="{{$user->class_id}}"></Questions>
            </v-app>
        </div>
    </div>
<script src="{{asset('/js/app.js')}}"></script>

<script src="{{asset('/js/jquery.js')}}"></script>
</body>
</html>
