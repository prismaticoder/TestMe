{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('/js/jquery.js')}}"></script>
    <script src="{{asset('/js/functions.js')}}"></script>
    <title>Student Examination</title>
</head>
<body>
    <span class="name">{{$name}}</span><br>
    <span class="subject">{{strtoupper($subject->alias)}}</span><br>
    <span class="class">{{$user->class_id}}</span><br>
    <div class="question">
            This is a question<br>
    </div>
    <ul>
        <form id="options" autocomplete="off">
                        <li> A: <input type="radio" name="options" value="0" id=""> <span class=options></span></li>
                        <li> B: <input type="radio" name="options" value="1" id=""> <span class=options></span></li>
                        <li> C: <input type="radio" name="options" value="2" id=""> <span class=options></span></li>
                        <li> D: <input type="radio" name="options" value="3" id=""> <span class=options></span></li>
        </form>
    </ul>

    <button class="prevButton">PREVIOUS</button>
    <button class="nxtButton" id="1">NEXT</button>

        <a href="{{route('logout')}}">Logout</a>

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

</body>
</html>
