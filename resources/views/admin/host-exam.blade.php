<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Exam Page</title>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      /*
        * Globals
        */

        /* Links */
        a,
        a:focus,
        a:hover {
        color: #fff;
        }

        /* Custom default button */
        .btn-secondary,
        .btn-secondary:hover,
        .btn-secondary:focus {
        color: #333;
        text-shadow: none; /* Prevent inheritance from `body` */
        background-color: #fff;
        border: .05rem solid #fff;
        }


        /*
        * Base structure
        */

        html,
        body {
        height: 100%;
        background-color: #333;
        }

        body {
        display: -ms-flexbox;
        display: flex;
        color: #fff;
        text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
        box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
        }

        .cover-container {
        max-width: 42em;
        }


        /*
        * Header
        */
        .masthead {
        margin-bottom: 2rem;
        }

        .masthead-brand {
        margin-bottom: 0;
        }

        .nav-masthead .nav-link {
        padding: .25rem 0;
        font-weight: 700;
        color: rgba(255, 255, 255, .5);
        background-color: transparent;
        border-bottom: .25rem solid transparent;
        }

        .nav-masthead .nav-link:hover,
        .nav-masthead .nav-link:focus {
        border-bottom-color: rgba(255, 255, 255, .25);
        }

        .nav-masthead .nav-link + .nav-link {
        margin-left: 1rem;
        }

        .nav-masthead .active {
        color: #fff;
        border-bottom-color: #fff;
        }

        @media (min-width: 48em) {
        .masthead-brand {
            float: left;
        }
        .nav-masthead {
            float: right;
        }
        }


        /*
        * Cover
        */
        .cover {
        padding: 0 1.5rem;
        }
        .cover .btn-lg {
        padding: .75rem 1.25rem;
        font-weight: 700;
        }


        /*
        * Footer
        */
        .mastfoot {
        color: rgba(255, 255, 255, .5);
        }

    </style>
    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="masthead mb-auto">
    <div class="inner">
      <h3 class="masthead-brand">OASIS-CBT ADMIN</h3>
      <nav class="nav nav-masthead justify-content-center">
      <a class="nav-link active" href="{{route('dashboard')}}">Home</a>
        {{-- <a class="nav-link" href="#">Features</a>
        <a class="nav-link" href="#">Contact</a> --}}
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">


    <h1 class="cover-heading">{{$subject->subject_name}} Exam</h1><br>
    <p class="lead">Instructions</p>
  <p class="lead">All students can now access the exam page via <strong style="color:orange">{{route('exam',['subject'=>$subject->alias])}}</strong>. Click the "End Exam" button after the exam is fully over to disable student access.</p>
    <p class="lead">Exam Duration</p>
    @foreach ($marks as $mark)
  <h1 class="cover-heading">JSS{{$mark->class_id}} 0{{$mark->hours}}:{{($mark->minutes == 0)?"00":$mark->minutes}}:00</h1><br>
    @endforeach

    <p class="lead">
    <a href="{{route('end-exam', ['subject'=>$subject->alias])}}" class="btn btn-lg btn-secondary" onclick="return confirm('Are you sure you want to end this examination for all students thereby disabling any form of student access?')">End Exam</a>
    </p>
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>&copy;2019 OASIS-CBT ADMIN</p>
    </div>
  </footer>
</div>
</body>
</html>
