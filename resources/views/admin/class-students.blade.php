{{-- Page showing all students of each class with links to add, edit or delete the students --}}


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <title>Admin Dashboard</title>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <style>
        nav {
            margin-bottom: 300px;
        }
    </style>
  </head>
  <body>

  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">One-Time Schools</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container">
        <h2>CLass</h2>
          <div class="table-responsive">
          
          <table class="table table-sm table-hover table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Registration Number</th>
                  <th scope="col">Firstname</th>
                  <th scope="col">Lastname</th>
                  <th scope="col">Handle</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Mark</td>
                  <td>Otto</td>
                  <td>@mdo</td>
                </tr>
                @foreach ($students as $student )
                    <tr>
                        <td scope="row">{{$student->firstname}}</td>
                        <td>{{$student->lastname}}</td>
                        <td>{{$student->code}}</td>
                    </tr>
                @endforeach
                
              </tbody>
            </table>
          </div>
          </div>
    </body>
</html>