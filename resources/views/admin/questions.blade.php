{{-- This is where the admin adds questions for each subject, edits, deletes and views them. SUmmernote will be on this page --}}
@foreach ($questions as $question)
    {{$question['question']}}<br>
    {{$question['options']}}

@endforeach
