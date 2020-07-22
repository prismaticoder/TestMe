{{-- This is the view for the admin main page. It contains the list of all subjects with links to host exam, add questions, view results, it also has a navbar with links to the students page --}}

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <title>Oasis CBT | Admin Dashboard</title>


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
  <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('dashboard')}}">OASIS-CBT ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link active" href="{{route('dashboard')}}">Dashboard <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{route('results')}}">Results</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}#students">Students</a>
            </li>
            @can('superAdminGate')
            <li class="nav-item">
            <a class="nav-link" href="{{route('Admins-section')}}">Admins Section</a>
            </li>
            @endcan
            </ul>
            <span class="navbar-text">
            <a class="nav-link" href="{{route('admin-logout')}}">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="container">
        <main role="main">
          <h1 style = "margin-top:120px">Admin Section page</h1>
          <div class="card-deck">
            <table class = "table table-responsive">
                <tr>
                    <th>Role</th>
                </tr>
                <form action = "{{route('update-admin')}}" method = 'POST'>
                @csrf
                {{ method_field ('POST')}}
                @foreach($roles as $role)
                <tr>  
                    <td>
                        <input type = "checkbox" name = "role">{{$role->role}}     
                    </td>                  
                </tr>
                @endforeach
                <input type = "submit" name = "submit" value ="update">
                </form> 
            </table>
          </div>
          </main>

    </div>
  </body>
</html>
