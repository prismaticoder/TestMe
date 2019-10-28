{{-- This is where the admin adds questions for each subject, edits, deletes and views them. SUmmernote will be on this page --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Questions Page</title>
    <!-- include libraries(jQuery, bootstrap) -->
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

    <!-- include summernote css/js-->
    <link href="{{asset('css/summernote.css')}}" rel="stylesheet">
    <script src="{{asset('js/summernote.js')}}"></script>

    <script>
            $(document).ready(function() {
                $('#summernote').summernote({
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

    </head>
<body>
<form action="{{route('questions',['subject'=>$subject->alias,'class_id'=>$class_id])}}" method="post">
        @csrf
        <textarea name="question" id="summernote"></textarea>

        <label for="optionA">Option A</label>
        <input type="text" name="optionA" id="optionA"><br>
        <label for="optionB">Option B</label>
        <input type="text" name="optionB" id="optionB"><br>
        <label for="optionC">Option C</label>
        <input type="text" name="optionC" id="optionC"><br>
        <label for="optionD">Option D</label>
        <input type="text" name="optionD" id="optionD">

        <ul>
        <li><input type="radio" name="correct" id="A" value="optionA">A</li>
        <li><input type="radio" name="correct" id="B" value="optionB">B</li>
        <li><input type="radio" name="correct" id="C" value="optionC">C</li>
        <li><input type="radio" name="correct" id="D" value="optionD">D</li>
        </ul>

        <input type="submit" value="Submit question">
    </form>
</body>
</html>
@foreach ($questions as $question)
    {!!$question->question!!}<br>
    {{$question->options}}

@endforeach
