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
        height: 100%;
        }

        body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        .question-body input {
            margin-right: 15px;
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
                <a class="nav-link" href="#">Subject <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
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

    

    <div class="container question-body">
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

        <form action="" method="post" id="reloadForm">
            <input type="hidden" name="subject" id="subjectH">
            <input type="hidden" name="class" id="classH">
            <input type="hidden" name="question_id" id="questionH">
        </form>

    
    
    <script src="{{asset('/js/jquery.js')}}"></script>
    <script src="{{asset('/js/functions.js')}}"></script>
</body>
</html>
