{{-- Welcome to our SPA. This is the view for the exam the student will be undertaking --}}
@php $n = 0 @endphp
@foreach ($questions as $question)
    {!!$question->question!!}<br>
    <form action="" method="POST">
        @php $letter = "A" @endphp
        <ul>
            @foreach ($question->options as $option)
                <li> {{$letter}}: <input type="radio" name="options" id=""> {{$option->body}}</li>
                @php $letter++ @endphp
            @endforeach
        </ul>
        <input type="submit" value="NEXT"><br>
    </form>
@endforeach


{{$questions->links()}}
