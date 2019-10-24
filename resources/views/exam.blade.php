{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
@foreach ($questions as $question)
    <b>Question</b> {{$question->question}}<br>

    @foreach ($question->options as $option)
        Option: <pre>{{$option->body}}</pre>
    @endforeach

@endforeach
