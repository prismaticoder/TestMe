{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
@foreach ($questions as $question)
    {{$question->question}}<br>
    {{$question->options}}

@endforeach
