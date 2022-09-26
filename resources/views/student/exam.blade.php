{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="icon" href="{{asset('/img/logo.png')}}">


    <title>{{config('app.name')}} | {{config('app.schoolAlias')}} - {{ucfirst($subject->name). ' ' . auth()->user()->class->name}} Examination</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <style>
        body {
        background-color: #f5f5f5;

        }
        .sidebar {
            padding: 70px 0 20px;
            height: 100vh;
            overflow-y: scroll;
            background-color: #fff;
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
                {{config('app.name')}} | {{config('app.schoolAlias')}} {{strtoupper($subject->name) . ' ' . auth()->user()->class->name}} EXAMINATION
            </a>

            <timer 
                :hours="{{$hours}}"
                :minutes="{{$minutes}}"
                :exam-id="{{$exam->id}}"
                :student-id="{{auth()->id()}}"
            >
            </timer>
        </nav>

        <div>
            {{-- made it class-id(kebab-case) because HTML converts it to lowercase automatically --}}
            <v-app>
                <questions 
                    :questions="{{$questions}}"
                    :exam-id="{{$exam->id}}"
                    :student-id="{{auth()->id()}}"
                    :student="{{auth()->user()}}"
                    :hours="{{$hours}}"
                    :minutes="{{$minutes}}"
                    :subject="{{$subject}}"
                >
                </questions>
            </v-app>
        </div>
    </div>
<script src="{{asset('/js/app.js')}}"></script>
<script src="{{asset('/js/functions.js')}}"></script>

</body>
</html>
