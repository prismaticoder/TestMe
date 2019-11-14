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
            </ul>
            <span class="navbar-text">
            <a class="nav-link" href="{{route('admin-logout')}}">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="container" id="students">

        <main role="main" class="classes">
          <h1>Students</h1>
          <div class="card-deck">
              @foreach ($classes as $class)
                <div class="card">
                    <div class="card-body">
                    <a href="{{route('class-students',['class'=>$class->class])}}" class="text-dark">
                      <h5 class="card-title">Junior Secondary School {{$class->id}}</h5>
                      <p class="card-text">{{$class->students()->count()}} Students</P>
                    </div>
                    <div class="card-footer">
                    <small class="text-muted"><a href="{{route('class-students',['class'=>$class->class])}}">See Student List</a></small>
                    </div>
                </div>
              @endforeach
          </div>
          </main>

        <h1 style="margin-top:50px;">Subjects</h1>

        <main class="card-columns">
          @foreach ($subjects as $subject)
          <div class="card">
            <div class="card-body">
              <p class="card-text">{{strtoupper($subject->alias)}}</p>
            </div>
            <div class="card-footer">
              <small class="text-muted">
              <a href="" class="btn btn-primary host-exam" id="{{$subject->alias}}">Start Exam</a>
              <a href="{{route('questions',['subject'=>$subject->alias,'class_id'=>1])}}" class="btn btn-secondary">JSS 1</a>
              <a href="{{route('questions',['subject'=>$subject->alias,'class_id'=>2])}}" class="btn btn-secondary">JSS 2</a>
              <a href="{{route('questions',['subject'=>$subject->alias,'class_id'=>3])}}" class="btn btn-secondary">JSS 3</a>
              </small>
            </div>
          </div>
          @endforeach
        </main>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form>
                <div class="row">
                <div class="col-sm-5">
                    <label for="">Exam Duration</label>
                  </div>
                  <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Hour">
                  </div>
                  <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Minutes">
                  </div>
                </div>
                <div class="form-group row mt-3">
                  <label for="colFormLabel" class="col-sm-5 col-form-label">Total Allotable Marks</label>
                  <div class="col-sm-4">
                    <input type="number" class="form-control" id="colFormLabel" placeholder="score">
                  </div>
                </div>
              </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Start Exam</button>
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

    <!-- Icons -->
    {{-- <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script> --}}
    <script>
    //   feather.replace()
      $('.host-exam').on('click',function(event) {
          event.preventDefault();
          var id = $(this).attr('id');
          $.ajax({
              url:'/checkMark/'+id,
              method:'GET',
              success:function(response) {
                  if (response == 'Yes') {
                      window.location.href = "/admin/"+id+"/hostexam";
                  }
                  else {
                      alert('You are not allowed to start this examination because the time duration has not been set for one or more of the classes that would be taking this exam. Check through the classes and set the duration where necessary')
                  }
              },
              error:function(response) {
                  console.log(response);
              }

          })
      })
    </script>

  </body>
</html>
