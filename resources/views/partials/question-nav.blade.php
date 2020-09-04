<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
    <a href="{{route('dashboard')}}">
        <img src="{{asset('/img/logo.png')}}" class="mr-2" height="45" width="45">
    </a>
    <a class="navbar-brand" href="{{route('dashboard')}}">
        {{config('app.name')}} | {{config('app.schoolAlias')}} ADMIN (Go To Dashboard)
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="#">{{strtoupper($subject->alias)}} <span class="sr-only">(current)</span></a>
        </li>
        @foreach ($subject->classes as $class)
            <li class="nav-item">
            <a class="nav-link {{ (request()->segment(4) == $class->id) ? 'active' : '' }}" href="{{\Request::route()->getName() == 'questions' ? route('questions',['subject'=>$subject->alias,'class_id'=>$class->id]) : route('results',['subject'=>$subject->alias,'class_id'=>$class->id])}}">JSS {{$class->id}}</a>
            </li>
        @endforeach
        </ul>
        <span>
            <a class="mt-2 nav-link logoutBtn" href="{{route('admin-logout')}}">Sign Out</a>
        </span>
    </div>
</nav>
