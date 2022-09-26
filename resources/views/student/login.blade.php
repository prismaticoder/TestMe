<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('/img/logo.png')}}">

    <title>{{config('app.name')}} | {{config('app.schoolAlias')}} - Student Login</title>

    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">

</head>

  <body class="text-center">
    <form class="form-signin" autocomplete="off" method="POST" action="{{ route('login') }}">

        <h1>Student Login</h1>
        @csrf

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    <strong>{{$error}}</strong><button type="button" class="close" data-dismiss='alert' aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                </div>
            @endforeach
        @endif

        <label for="examination_number" class="mt-3">{{ __('Examination Number') }}</label>

        <input id="examination_number" type="text" class="form-control text-uppercase" name="examination_number" required>

        <div >
            <button type="submit" class="btn loginBtn btn-block mt-4">
                {{ __('Login') }}
            </button>
        </div>
    </form>
  </body>

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
</html>
