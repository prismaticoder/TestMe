{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
@foreach ($questions as $question)
    <b>Question</b> {{$question->question}}<br>

    @php $letter = "A" @endphp
    @foreach ($question->options as $option)
        Option {{$letter}}: <pre>{{$option->body}}</pre>
        @php $letter++ @endphp
    @endforeach

@endforeach
