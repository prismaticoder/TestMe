{{-- General results page --}}

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <title>Results Page</title>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

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

        .card-text {
          font-size: 20px !important;
          font-weight: bold;
        }

    </style>
  </head>

  <body>
  <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('dashboard')}}">OASIS-CBT ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{route('dashboard')}}">Dashboard</a>
            </li>
            <li class="nav-item active">
            <a class="nav-link active" href="{{route('results')}}">Results</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}#students">Students</a>
            </li>
            </ul>
            <span class="navbar-text">
            <a class="nav-link" href="{{route('admin-logout')}}">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="container">



        <h1 style="margin-top:120px;">Exam Results Page</h1>

        <main class="card-columns mt-3">
            @foreach ($subjects as $subject)
            <div class="card">
                    <div class="card-body">
                      <p class="card-text">{{strtoupper($subject->alias)}}</p>
                    </div>
                    <div class="card-footer">
                      {{-- <small class="text-muted"> --}}
                      Class results >
                      @foreach ($classes as $class)
                      <a href="{{route('singleresult',['subject'=>$subject->alias,'class_id'=>$class->id])}}" class="btn btn-secondary">JSS {{$class->id}}</a>
                      @endforeach
                      {{-- </small> --}}
                    </div>
            </div>
            @endforeach
        </main>
        </div>
        </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../assets/js/vendor/popper.min.js"></script>

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

  </body>
</html>
