{{-- This is where the admin adds questions for each subject, edits, deletes and views them. SUmmernote will be on this page --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
<title>Oasis CBT | JSS{{$class_id}} {{$subject->subject_name}} Questions</title>
    <!-- include libraries(jQuery, bootstrap) -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('/js/functions2.js')}}"></script>
    {{-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"> --}}

    <!-- include summernote css-->
    <link href="{{asset('css/summernote-bs4.css')}}" rel="stylesheet">

    <!-- include summernote js-->
    <script src="{{asset('js/summernote-bs4.js')}}"></script>

    <script>
            $(document).ready(function() {
                $('#summernote').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['bold', 'underline','italic']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['picture']],
                        ],
            });
            })
    </script>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .questionBtn {
            cursor: pointer;
        }
        .sidebar {
            padding: 70px 0 20px;
            height: 100vh;
            overflow-y: scroll;
            background-color: #fff;
        }
        .question-body {
           padding: 130px 100px 50px;
        }
    </style>

    </head>
<body class="container-fluid">
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="{{route('dashboard')}}">OASIS-CBT ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">{{strtoupper($subject->alias)}} <span class="sr-only">(current)</span></a>
            </li>
            @foreach ($classes as $class)
                <li class="nav-item">
                <a class="nav-link {{ (request()->segment(4) == $class->id) ? 'active' : '' }}" href="{{route('questions',['subject'=>$subject->alias,'class_id'=>$class->id])}}">JSS {{$class->id}}</a>
                </li>
            @endforeach
            </ul>
            <span class="navbar-text">
                <a class="nav-link" href="{{route('admin-logout')}}">Sign out</a>
            </span>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions List</h4>
            <div class="list-group" id="questionList">
                @foreach ($questions as $question)
                <span class="questionBtn list-group-item list-group-item-action" id="{{$question->id}}">Question {{$loop->iteration}}</span>
                @endforeach
            </div>
        </div>

    <div class="col-md-10 question-body ">
    <form id="myForm" autocomplete="off" action="{{route('questions',['subject'=>$subject->alias,'class_id'=>$class_id])}}" method="post">
        @csrf
        <textarea required name="question" id="summernote"></textarea><br>
        <div class="options">
            <div class="form-group row">
                <label for="optionA" class="col-sm-2 col-form-label">Option A</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control"  data-option-id="" id="optionA" name="optionA" placeholder="Option A">
                </div>
            </div>
            <div class="form-group row">
                <label for="optionB" class="col-sm-2 col-form-label">Option B</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control" data-option-id="" id="optionB" name="optionB" placeholder="Option B">
                </div>
            </div>
            <div class="form-group row">
                <label for="optionC" class="col-sm-2 col-form-label">Option C</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control" data-option-id="" id="optionC" name="optionC" placeholder="Option C">
                </div>
            </div>
            <div class="form-group row">
                <label for="optionD" class="col-sm-2 col-form-label">Option D</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control" data-option-id="" id="optionD" name="optionD" placeholder="Option D">
                </div>
            </div>
        </div>

        <div class="answer row">
            <br>
            <label for="op" class="col-sm-2 col-form-label">Correct Option</label>
            <br>
            <div class="col-sm-10">

            <div class="form-check form-check-inline">
                <input class="form-check-input" required name="correct" type="radio" id="inlineCheckbox1" value="0">
                <label class="form-check-label" for="inlineCheckbox1">A</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="correct" type="radio" id="inlineCheckbox2" value="1">
                <label class="form-check-label" for="inlineCheckbox2">B</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="correct" type="radio" id="inlineCheckbox3" value="2">
                <label class="form-check-label" for="inlineCheckbox3">C</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="correct" type="radio" id="inlineCheckbox4" value="3">
                <label class="form-check-label" for="inlineCheckbox4">D</label>
            </div>
            </div>
        </div>

        <br>
        <input type="hidden" name="" id="subject_id" value="{{$subject->id}}">
        <input type="hidden" name="" id="class_id" value="{{$class_id}}">
        <div>
            <button data-button-type="add-question" class="btn btn-secondary submitBtn">Submit Question</button>
        </div>
    </form>

    <br>
    {{-- <ul>
        @foreach ($questions as $question)
            <li><span class="questionBtn" id="{{$question->id}}">Question {{$loop->iteration}}</span></li>

        @endforeach
    </ul> --}}

    </div>
    </div>



</body>
</html>



