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

        .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
        }
        .form-signin .checkbox {
        font-weight: 400;
        }
        .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        }
        .form-signin .form-control:focus {
        z-index: 2;
        }
        .form-signin input[type="text"] {
        margin-bottom: -1px;
        }
        .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }
    </style>

</head>
<body>

    <div class="container sign-in">
        <div class="card" >
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                <p class="card-text" id="question">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <p>
                    <ul> Options
                        <li> A: <input type="radio" name="options" id=""> <span class=options></span></li>
                        <li> B: <input type="radio" name="options" id=""> <span class=options></span></li>
                        <li> C: <input type="radio" name="options" id=""> <span class=options></span></li>
                        <li> D: <input type="radio" name="options" id=""> <span class=options></span></li>
                    </ul>
                </p>
                <a href="#" class="btn btn-secondary card-link">Previous question</a>
                <a href="#" class="btn btn-primary card-link">Next Question</a>
                <a href="{{route('logout')}}">Logout</a>
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
