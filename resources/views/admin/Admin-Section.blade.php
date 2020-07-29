@extends('layouts.main')
@section('title', 'Teachers')
@section('pageHeader', 'Teacher Section')
@section('content')

    <main role="main" >
        <div class="card-deck">
        <table class ="table table-responsive table-bordered">
            <tr>
                <th>Admins username</th>
                <th>Admins roles</th>
                <th>Edit | Delete</th>
            </tr>


            @foreach($roles as $role)


            <tr>
                <td><b>{{$role->admin->username}}</b></td>
                <td><b>{{$role->role}}</b></td>
                <td>
                    <a href =" {{route('edit-admin', ['id'=> $role->admin->id])}} " type ="button" class = "btn btn-primary">Edit</a>
                    <a href =" {{route('destroy-admin')}} " type ="button" class = "btn btn-warning">Delete</a>
                </td>
            </tr>


            @endforeach
        </table>
        </div>
    </main>
@endsection
