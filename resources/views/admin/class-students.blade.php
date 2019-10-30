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
        td button {
          color: #fff;
          background-color: blue;
        }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('dashboard')}}">One Time Schools</a>
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
            <a class="nav-link" href="{{route('admin-logout')}}">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="container contents">
    <h2>JSS{{$class->id}} Student List <button href="#" class="btn btn-primary btn-sm ml-3" data-toggle="modal" data-target="#exampleModalCenter">ADD STUDENT</button></h2>

          <table class="table table-s table-hover table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th>Registration Number</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Class</th>
                  <th>Options</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($students as $student )
                    <tr>
                        <td>{{$student->code}}</td>
                        <td>{{$student->firstname}}</td>
                        <td>{{$student->lastname}}</td>
                        <td>JSS{{$student->class_id}}</td>
                        <td >
                          <button href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal">EDIT</button>
                          <button href="#" class="btn btn-secondary btn-sm">DELETE</button>
                        </td>
                    </tr>
                @endforeach

              </tbody>
            </table>
          </div>

          <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add New Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form>
                <div class="form-group row mt-3">
                  <label for="firstName" class="col-sm-4 col-form-label">First Name</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="firstName" placeholder="First Name">
                  </div>
                </div>
                <div class="form-group row mt-3">
                  <label for="lastName" class="col-sm-4 col-form-label">Last Name</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="lastName" placeholder="Last Name">
                  </div>
                </div>
              </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Add Student</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Student Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form>
                <div class="form-group row mt-3">
                  <label for="firstName" class="col-sm-4 col-form-label">First Name</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="firstName" placeholder="First Name">
                  </div>
                </div>
                <div class="form-group row mt-3">
                  <label for="lastName" class="col-sm-4 col-form-label">Last Name</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="lastName" placeholder="Last Name">
                  </div>
                </div>
              </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </div>
        </div>



        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
        <script src="../../assets/js/vendor/popper.min.js"></script>

        <script src="{{asset('js/jquery.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
    </body>
</html>
