<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="{{asset('/img/logo.png')}}">
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/main.css')}}">

    <title>{{config('app.name')}} | {{config('app.schoolAlias')}} - @yield('title')</title>

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
