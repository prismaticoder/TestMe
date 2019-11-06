{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
<title>Oasis CBT | {{$subject->subject_name}} Examination</title>

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
           padding: 130px 100px 50px;
           height: 100vh;
            overflow-y: scroll;
        }
        .question-body input {
            margin-right: 15px;
        }

        .radioBtn {
            width: 15px;
            height: 15px;
        }
        .sidebar {
            padding: 70px 0 20px;
            height: 100vh;
            overflow-y: scroll;
            background-color: #fff;
        }

        .radios:hover {
            border:solid #204d74 1px;
        }
    </style>

</head>
<body class="container-fluid">
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand navbar-text" style="color:white">OASIS ROYAL ACADEMY</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
            <a class="nav-link"><span>{{strtoupper($subject->subject_name)}}</span> JSS<span class="class">{{$user->class_id}}</span> <span class="sr-only">(current)</span></a>
            </li>
            <span style="display:none" class="subject">{{strtoupper($subject->alias)}}</span>
            <li class="nav-item">
            <a class="nav-link">{{$name}}</a>
            </li>
            <li class="nav-item">
            <a class="nav-link">Exam No: {{$user->code}}</a>
            </li>
            </ul>
            <span class="navbar-text center" style="margin-right:40px;">
            <h3>Timer: <span id="hours">{{$hours}}</span>h <span id="minutes">{{$minutes}}</span>m <span id="seconds">0</span>s</h3>
            </span>
            <span class="navbar-text">
            <button class="nav-link newButton btn btn-primary submitBtn" disabled data-button-type="submit">SUBMIT EXAMINATION</button>
            </span>
            <input type="hidden" name="" class="reloader" value="0">
        </div>
    </nav>

    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions List</h4>
            <div class="list-group questionListParent">
                {{-- <a href="#" class="list-group-item list-group-item-action active">
                    Cras justo odio
                </a> --}}
                @foreach ($questions as $question)
            <a href="#" data-button-type="next" data-question="{{$loop->iteration}}" class="newButton questionList list-group-item list-group-item-action disabled" id="{{$question->id}}">Question {{$loop->iteration}}</a>
                @endforeach
            </div>
        </div>


    <div class="col-md-10 question-body ">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Question No <span class="questionNo">0</span> of <span class="questionCount">{{$questions->count()}}</span></h5>
                <p class="card-text question">Click the "<strong>START</strong>" button to begin your examination</p>
            </div>
            <ul class="list-group list-group-flush">
                    <label for="radio1"><li class="list-group-item radios"><input type="radio" id="radio1" data-option-id="1" name="options" class="radioBtn" value="0"><span class="options"> -</span></li></label>
                    <label for="radio2"><li class="list-group-item radios"><input type="radio" id="radio2" data-option-id="2" name="options" class="radioBtn" value="1"><span class="options"> -</span></li></label>
                    <label for="radio3"><li class="list-group-item radios"><input type="radio" id="radio3" data-option-id="3" name="options" class="radioBtn" value="2"><span class="options"> -</span></li></label>
                    <label for="radio4"><li class="list-group-item radios"><input type="radio" id="radio4" data-option-id="4" name="options" class="radioBtn" value="3"><span class="options"> -</span></li></label>
            </ul>
            <div class="card-body">
                <button data-button-type="next" class="btn btn-secondary card-link prevButton newButton disabled">Previous Question</button>
                <button data-button-type="start" data-question="1"  class="btn btn-primary card-link nxtButton newButton">START</button>
            </div>
        </div>
    </div>



    </div>

        {{-- <p id="demo"></p>

        <script>
        // Set the date we're counting down to
        var countDownDate = new Date("Nov 5, 2019 02:50:00").getTime();

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
