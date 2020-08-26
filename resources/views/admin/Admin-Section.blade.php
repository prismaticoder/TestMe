@extends('layouts.main')
@section('title', 'Teachers')
@section('pageHeader', 'Administrative Section')
@section('content')


    <div id="app">
        <admin-section :allsubjects="{{$subjects}}" :allteachers="{{$teachers}}"></admin-section>
    </div>

    {{-- <main role="main" >
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
    </main> --}}

    <script src="{{asset('/js/app.js')}}"></script>
@endsection
