<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
    <a href="{{route('dashboard')}}">
        <img src="{{asset('/img/logo.png')}}" class="mr-2" height="45" width="45">
    </a>
    <a class="navbar-brand" href="{{route('dashboard')}}">
        {{config('app.name')}} | {{config('app.schoolAlias')}} ADMIN
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mx-auto col-md-3">
            <li class="nav-item">
                <a class="nav-link {{ (\Request::route()->getName() == 'dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}">Main Dashboard</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ (\Request::route()->getName() == 'results') ? 'active' : '' }}" href="{{route('results')}}">Results</a>
            </li> --}}
            @can('superAdminGate')
            <li class="nav-item">
                <a class="nav-link {{ (\Request::route()->getName() == 'teachers') ? 'active' : '' }}" href="{{route('teachers')}}">Teachers</a>
            </li>
            @endcan
        </ul>
        <span>
            <a class="mt-2 nav-link logoutBtn" href="{{route('admin-logout')}}">Sign Out</a>
        </span>
    </div>
</nav>
