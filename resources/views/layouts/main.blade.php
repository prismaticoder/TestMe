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
    @if (\Request::route()->getName() !== 'questions')
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
