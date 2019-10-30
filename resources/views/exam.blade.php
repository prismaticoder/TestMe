{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Student Examination</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <style>
        html,
        body {
        border: border-box;
        }

        body {
        background-color: #f5f5f5;
        
        }
        .question-body {
           padding: 130px 100px 0; 
        }
        .question-body input {
            margin-right: 15px;
        }
        .sidebar {
            padding: 70px 0;
            height: 100vh;
            overflow-y: scroll;
            background-color: #fff;
        }
    </style>

</head>
<body class="container-fluid">
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('dashboard')}}">One Time Schools</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Subject <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Student Name</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Exam No: 1234</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Time: 35 min left</a>
            </li>
            </ul>
            <span class="navbar-text">
            <a class="nav-link" href="{{route('logout')}}">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions List</h4>
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active">
                    Cras justo odio
                </a>
                <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
                <a href="#" class="list-group-item list-group-item-action" tabindex="-1" aria-disabled="true">Vestibulum at eros</a>
            </div>
        </div>
    

    <div class="col-md-10 question-body ">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Question No 13 of 60</h5>
                <p class="card-text" id="question">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><input type="radio" name="options" id="">A. Cras justo odio</li>
                <li class="list-group-item"><input type="radio" name="options" id="">B. Dapibus ac facilisis in</li>
                <li class="list-group-item"><input type="radio" name="options" id="">C. Vestibulum at eros</li>
                <li class="list-group-item"><input type="radio" name="options" id="">D. Vestibulum at eros</li>
            </ul>
            <div class="card-body">
                <a href="#" class="btn btn-secondary card-link">Previous Question</a>
                <a href="#" class="btn btn-primary card-link">Next Question</a>
            </div>
        </div>
    </div>

    </div>

        {{-- <p id="demo"></p>

        <script>
        // Set the date we're counting down to
        var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

          // Get today's date and time
          var now = new Date().getTime();

          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations for days, hours, minutes and seconds
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Display the result in the element with id="demo"
          document.getElementById("demo").innerHTML = days + "d " + hours + "h "
          + minutes + "m " + seconds + "s ";

          // If the count down is finished, write some text
          if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
          }
        }, 1000);
        </script> --}}



    <script src="{{asset('/js/jquery.js')}}"></script>
    <script src="{{asset('/js/functions.js')}}"></script>
</body>
</html>
