<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('/img/logo.png')}}">

  <title>{{config('app.name')}} | {{config('app.schoolAlias')}} - @yield('title')</title>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">

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

        .logoutBtn {
            color: white;
            border: solid #e67d23 2px;
            background-color: transparent
        }

        .logoutBtn:hover {
            background-color: #e67d23;
            color: black;

        }

        .questionBtn {
            cursor: pointer;
        }
        .sidebar {
            padding: 70px 0 20px;
            height: 90vh;
            overflow-y: scroll;
            background-color: #fff;
        }
        .classes {
          margin-top: 120px;
        }
        .card-text {
          font-size: 20px !important;
          font-weight: bold;
        }

    </style>
  </head>

  <body>
    @if (\Request::route()->getName() !== 'questions' && \Request::route()->getName() !== 'results')
        @include('partials.nav')

        <div class="container">

                <h3 class="text-center mt-5">
                    @yield('pageHeader')
                </h3>
                <hr>

            @yield('content')
        </div>
    @else
        @include('partials.question-nav')
        @yield('content')
    @endif

  </body>
</html>
