<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Role;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\User;
use App\Subject;
use App\Classes;
use Gate;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;

class AdminSectionController extends Controller
{

    public function index() {

        $roles = Role::get();

        return view('admin.Admin-Section')->with([
           'roles'=>$roles
        ]);

    }

    public function createAdmin(Request $request) {
        $request->validate([
            'username' => ['required', 'string', 'unique:admins', 'max:255'],
            'password' => ['required', 'string'],
        ]);


        $admin = new Admin;
        $admin->username = $request->username;
        $admin->password = $request->password;
        $admin->subject_id = null;
        $admin->role_id = 1;

        $admin->save();

        $message = "New administrator added successfully";

        return compact('admin','message');
    }

    public function updateAdmin(Request $request, $id) {
        $request->validate([
            'username' => ['required', 'string', 'unique:admins', 'max:255'],
            'password' => ['required', 'string']
        ]);

        if (Auth::id() === $id) {
            $admin = Admin::find($id);
            $admin->username = $request->username;
            $admin->password = $request->password;

            $admin->save();

            $message = "Your details were updated successfully";

            return compact('admin','message');
        }

        return abort (401, "You are not authorized to perform this action");
    }

    public function getAllTeachers() {
        $teachers = Admin::where('role_id',2)->get();

        return compact('teachers');
    }


    public function createTeacher(Request $request) {
        $request->validate([
            'username' => ['required', 'string', 'unique:admins', 'max:255'],
            'password' => ['required', 'string'],
            'subject_id' => ['required', 'integer'],
        ]);

        $check = Subject::find($request->subject_id);

        if ($check) {
            $teacher = new Admin;
            $teacher->username = $request->username;
            $teacher->password = $request->password;
            $teacher->subject_id = $request->subject_id;
            $teacher->role_id = 2;

            $teacher->save();

            $message = "Teacher created successfully";

            return compact('teacher','message');
        }

        return abort(404, $message = "Subject not found");
    }


    public function updateTeacher(Request $request, $id) {
        $request->validate([
            'username' => ['required', 'string', 'unique:admins', 'max:255'],
            'password' => ['required', 'string']
        ]);

        if (Auth::id() === $id) {
            $teacher = Admin::find($id);
            $teacher->username = $request->username;
            $teacher->password = $request->password;

            $teacher->save();

            $message = "Your details were updated successfully";

            return compact('teacher','message');
        }

        return abort (401, "You are not authorized to perform this action");
    }

    public function deleteTeacher($id) {

        $teacher = Admin::find($id);
        $teacher->delete();

        $message = "Deletion successful!";

        return compact('message');
    }


    public function edit(Request $request)
    {
        // roles and admin => one to one relationship
        $roles = Role::all();
        // $request->flashOnly($roles->id);
        return view('admin.Admin-Section-edit')->with(
            ['roles' => $roles]
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $admin = Admin::where('id', $request->id)->first();
        $admin->username = $request->username;
        $admin->password = $request->password;
        $admin->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $roles = Role::where('id', $request->id)->first();
        $roles->delete();

    }


}



