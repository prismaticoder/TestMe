{{-- Admin Login Page --}}

<form action="{{route('admin-login')}}" method="post">
    @csrf

    @foreach ($error as $err)
        {{$err}}
    @endforeach<br>
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>

    <input type="submit" value="Submit Details">
</form>
