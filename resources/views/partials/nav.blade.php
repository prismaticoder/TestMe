<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('dashboard')}}">OASIS-CBT ADMIN</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link {{ (\Request::route()->getName() == 'dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}">Main Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ (\Request::route()->getName() == 'results') ? 'active' : '' }}" href="{{route('results')}}">Results</a>
            </li>
            @can('superAdminGate')
            <li class="nav-item">
                <a class="nav-link {{ (\Request::route()->getName() == 'teachers') ? 'active' : '' }}" href="{{route('teachers')}}">Teachers</a>
            </li>
            @endcan
        </ul>
        <span class="btn btn-light">
            <a class="nav-link text-dark" href="{{route('admin-logout')}}">Sign out</a>
        </span>
    </div>
</nav>
