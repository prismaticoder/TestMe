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
        h2 {
            margin-top: 120px;
            text-align: center;
        }
    </style>
  </head>
  <body>
    
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="#">One-Time Schools</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
            </li>
            </ul>
            <span class="navbar-text">
                <a class="nav-link" href="#">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="container contents">
        <h2>JSS One Students List</h2>
          
          <table class="table table-s table-hover table-bordered">
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
                  <td >1</td>
                  <td>Mark</td>
                  <td>Otto</td>
                  <td>@mdo</td>
                </tr>
                @foreach ($students as $student )
                    <tr>
                        <td scope="col">{{$student->firstname}}</td>
                        <td scope="col">{{$student->lastname}}</td>
                        <td scope="col">{{$student->code}}</td>
                        <td scope="col">{{$student->code}}</td>
                    </tr>
                @endforeach
                
              </tbody>
            </table>
          </div>
    </body>
</html>