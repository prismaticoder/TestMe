
{{-- This is the view for the student home page. It contains the list of all subjects with links to start exam --}}

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">
    <link rel="icon" href="{{asset('/img/logo.png')}}">

    <title>{{config('app.name')}} | {{config('app.schoolAlias')}} - All Current Exams</title>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"> --}}

    <!-- Custom styles for this template -->
    <style>
        body {
        font-size: .875rem;
        }

        .feather {
        width: 16px;
        height: 16px;
        vertical-align: text-bottom;
        }

        .classes {
          margin-top: 120px;
        }
        .card-text {
          font-size: 20px !important;
          font-weight: bold;
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

        .logoutBtn, .examBtn:hover {
            color: white;
            border: solid #e67d23 2px;
            background-color: transparent
        }

        .examBtn:hover {
            color: black
        }

        .logoutBtn:hover, .examBtn {
            background-color: #e67d23;
            color: black;

        }


    </style>
  </head>

  <body>
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
        <a href="/">
            <img src="{{asset('/img/logo.png')}}" class="mr-2" height="45" width="45">
        </a>
        <a class="navbar-brand" href="{{route('home')}}">
            {{config('app.name')}} | {{config('app.schoolAlias')}} HOME
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mx-auto col-md-2">
                <li class="nav-item">
                    <a class="nav-link active" href="">{{Auth::user()->fullName}} ({{Auth::user()->class->class}})</a>
                </li>
            </ul>
            <span>
                <a class="mt-2 nav-link logoutBtn" href="{{route('logout')}}">Sign Out</a>
            </span>
        </div>
    </nav>



    <div class="container">

        <h3 class="mt-5 text-center">All Current Exams</h3>
        <hr>

        @if (count($exams) > 0)
        <main class="card-columns mt-3">
            @foreach ($exams as $exam)
            <div class="card">
            <div class="card-body">
                  <p class="card-text">{{strtoupper($exam->subject->subject_name)}}</p>
                </div>
                <div class="card-footer">
                  <small class="text-muted">
                  <a href="{{route('exam',['subject'=>$exam->subject->alias])}}" class="btn examBtn">Start Exam</a>
                  </small>
                </div>
              </div>
            @endforeach
      </main>
        @else
        <p class="mt-3">
            Please wait for an exam to be started by the invigilator...
        </p>
        @endif


        </div>
  </body>
</html>
