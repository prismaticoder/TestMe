{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
@php $n = 1 @endphp
@foreach ($questions as $question)
    {{$n . '. '. $question->question}}<br>

    @php $letter = "A" @endphp
    <ul>
    @foreach ($question->options as $option)
        <li> {{$letter}}: {{$option->body}}</li>
        @php $letter++ @endphp
    @endforeach
    </ul>
    @php $n++ @endphp
@endforeach
