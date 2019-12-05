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
    {{-- <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    {{-- <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    {{-- <script src="{{asset('js/bootstrap.min.js')}}"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{asset('/js/functions2.js')}}"></script>
    {{--  --}}

    <!-- include summernote css-->
    <link href="{{asset('css/summernote-bs4.css')}}" rel="stylesheet">

    <!-- include summernote js-->
    <script src="{{asset('js/summernote-bs4.js')}}"></script>

     <!-- include summernote js-->
     <script src="{{asset('js/summernote-ext-specialchars.js')}}"></script>

     <!-- include summernote js-->
     <script src="{{asset('js/summernote-cleaner.js')}}"></script>
    <script>
            $(document).ready(function() {
                $('#summernote').summernote({
                    minHeight: 400,
                    toolbar: [
                        // ['cleaner',['cleaner']], // The Button
                        ['style', ['bold', 'underline','italic']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['picture']],
                        ['insert', [ 'specialchars' ]],
                        ],
                    cleaner: {
                        action: 'paste', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                        newline: '<br>', // Summernote's default is to use '<p><br></p>'
                        notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                        icon: '<i class="note-icon">[Your Button]</i>',
                        keepHtml: false, // Remove all Html formats
                        keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                        keepClasses: false, // Remove Classes
                        badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                        badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                        limitChars: false, // 0/false|# 0/false disables option
                        limitDisplay: 'both', // text|html|both
                        limitStop: false // true/false
                    }
            });

            $('.option').summernote({
                    minHeight: 150,
                    toolbar: [
                        ['style', ['bold', 'underline','italic']],
                        ['insert', [ 'specialchars' ]],
                        ],
                    cleaner: {
                        action: 'paste', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                        newline: '<br>', // Summernote's default is to use '<p><br></p>'
                        notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                        icon: '<i class="note-icon">[Your Button]</i>',
                        keepHtml: false, // Remove all Html formats
                        keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                        keepClasses: false, // Remove Classes
                        badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                        badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                        limitChars: false, // 0/false|# 0/false disables option
                        limitDisplay: 'both', // text|html|both
                        limitStop: false // true/false
                    }
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

        .form-check-input {
            width: 25px;
            height: 25px;
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

        .deleteBtn:hover {
            /* padding: 1px; */
            /* border: solid black 2px; */
            color: #f13c20;
            text-decoration: none;
            transition-duration: 0.01s;
            border-radius: 50%;
            cursor:default;
        }
    </style>

    </head>
<body class="container-fluid">
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="{{route('dashboard')}}">OASIS-CBT ADMIN (Go To Dashboard)</a>
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
    <span id="top"></span>

    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions <button style="float:right" title="New Question" data-button-type="add-question" class="addBtn btn btn-secondary"><i class="fa fa-plus"></i> New</button></h4>
            <div class="list-group" id="questionList">
                @foreach ($questions as $question)
            <span class="questionBtn list-group-item list-group-item-action" id="{{$question->id}}">Question {{$loop->iteration}} <i id="{{$question->id}}" title="Delete Question" class="deleteBtn fa fa-2x fa-close" style="float:right"></i></span>
                @endforeach
            </div>
        </div>



    <div class="col-md-10 question-body ">
            <div class="row">
                @if ($mark != "nil")
                <span class="col-md-2"></span><span class="col-md-2"></span><span class="col-md-2"></span><span class="col-md-2"></span><button id="showModal" style="float:right" href="#" class="btn btn-primary col-md-4" data-toggle="modal" data-target="#exampleModalCenter">UPDATE EXAM TIME AND TOTAL SCORE</button>

                @else
                <span class="col-md-2"></span><span class="col-md-2"></span><span class="col-md-2"></span><span class="col-md-2"></span><button id="showModal" style="float:right" href="#" class="btn btn-primary col-md-4" data-toggle="modal" data-target="#exampleModalCenter">SET EXAM TIME AND TOTAL SCORE</button>
                @endif
            </div>
            <br>

    <form id="myForm" autocomplete="off" action="{{route('questions',['subject'=>$subject->alias,'class_id'=>$class_id])}}" method="post">
        @csrf
        <textarea required name="question" id="summernote"></textarea><br>
        <div class="options">
            <div class="form-group row">
                <label for="optionA" class="col-sm-2 col-form-label">Option A</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control"  data-option-id="" id="optionA" name="optionA">
                </div>
            </div>
            <div class="form-group row">
                <label for="optionB" class="col-sm-2 col-form-label">Option B</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control" data-option-id="" id="optionB" name="optionB">
                </div>
            </div>
            <div class="form-group row">
                <label for="optionC" class="col-sm-2 col-form-label">Option C</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control" data-option-id="" id="optionC" name="optionC">
                </div>
            </div>
            <div class="form-group row">
                <label for="optionD" class="col-sm-2 col-form-label">Option D</label>
                <div class="col-sm-10">
                <input type="text" required class="option form-control" data-option-id="" id="optionD" name="optionD">
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

    @if ($mark == "nil")
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle">SET EXAM TIME AND TOTAL SCORE</h5>
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
                      <input type="number" required min="0" max="5" required class="form-control hours" placeholder="Hour">
                    </div>
                    <div class="col-sm-3">
                      <input type="number" required min="0" max="59" required class="form-control minutes" placeholder="Minutes">
                    </div>
                  </div>
                  <div class="form-group row mt-3">
                    <label for="colFormLabel" class="col-sm-5 col-form-label">Total Allocatable Marks</label>
                    <div class="col-sm-4">
                      <input type="number" required min="10" max="100" class="form-control scores" id="colFormLabel" placeholder="Total Marks">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" data-button-type="set" class="btn btn-primary markSubmit" value="Submit">
                </div>
            </form>

              </div>
            </div>
          </div>
        @else
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalCenterTitle">UPDATE EXAM TIME AND TOTAL SCORE</h5>
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
                        <input type="number" required min="0" max="5" required class="form-control hours" placeholder="Hour" value="{{$mark->hours}}">
                        </div>
                        <div class="col-sm-3">
                          <input type="number" required min="0" max="59" required class="form-control minutes" placeholder="Minutes" value="{{$mark->minutes}}">
                        </div>
                      </div>
                      <div class="form-group row mt-3">
                        <label for="colFormLabel" class="col-sm-5 col-form-label">Total Allocatable Marks</label>
                        <div class="col-sm-4">
                          <input type="number" required min="10" max="100" required class="form-control scores" id="colFormLabel" placeholder="Total Marks" value="{{$mark->mark}}">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" data-button-type="update" class="btn btn-primary markSubmit" id="{{$mark->id}}" value="Submit">
                    </div>
                </form>
                    </div>
                  </div>
                </div>
              </div>
    @endif



    {{-- <ul>
        @foreach ($questions as $question)
            <li><span class="questionBtn" id="{{$question->id}}">Question {{$loop->iteration}}</span></li>

        @endforeach
    </ul> --}}

    </div>
    </div>




</body>
</html>



