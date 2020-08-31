<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Role;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\AdminSubject;
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

        $subjects = Subject::with('classes')->get();
        $teachers = Admin::where('role_id', 2)->with(['subjects' => function ($q) {
            $q->with('classes','subject');
        }])->get();

        $classes = Classes::all();

        return view('admin.Admin-Section', compact('classes','subjects','teachers'));

    }

    public function createSubject(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'unique:subjects,subject_name'],
            'alias' => ['required', 'string', 'unique:subjects'],
            'classes' => ['required', 'array']
        ]);


        $subject = new Subject;
        $subject->subject_name = $request->name;
        $subject->alias = $request->alias;

        $subject->save();

        $subject->classes()->sync($request->classes);

        $subject->load('classes');

        $message = "Subject created successfully";

        return compact('subject','message');
    }

    public function updateSubject(Request $request, $id) {
        // $request->validate([
        //     'name' => ['required', 'string', 'unique:subjects,subject_name'],
        //     'alias' => ['required', 'string', 'unique:subjects'],
        //     'classes' => ['required', 'array']
        // ]);


        $subject = Subject::find($id);

        if ($subject) {
            $subject->subject_name = $request->name;
            $subject->alias = $request->alias;

            $subject->save();

            $subject->classes()->sync($request->classes);

            $subject->load('classes');

            $message = "Update successful";

            return compact('subject','message');
        }

        return abort(404);
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
            'subjects' => ['required', 'array'],
            'classes' => ['required', 'array'],
        ]);


        $teacher = new Admin;
        $teacher->username = $request->username;
        $teacher->password = bcrypt($request->password);
        $teacher->role_id = 2; //teacher's role_id

        $teacher->save();

        foreach ($request->subjects as $subject) {
            $admin_subject = new AdminSubject;
            $admin_subject->admin_id = $teacher->id;
            $admin_subject->subject_id = $subject->subject_id;
            $admin_subject->save();

            $admin_subject->classes()->sync($subject->classes);
            // $teacher->subjects()->save($subject->subject_id);
        }

        $message = "Teacher created successfully";

        return compact('teacher','message');
    }


    public function updateTeacher(Request $request, $id) {
        $request->validate([
            'subjects' => ['required', 'array'],
        ]);


        $teacher = Admin::find($id);

        $subject_indexes = array_map(function($element) {
            return $element['subject_id'];
        }, $request->subjects);

        $teacher->subjects()->whereNotIn('subject_id',$subject_indexes)->delete();

        foreach ($request->subjects as $subject) {
            $check = AdminSubject::where('admin_id',$teacher->id)->where('subject_id',$subject['subject_id'])->first();
            if ($check) {
                $check->classes()->sync($subject['classes']);
            }

            else {
                $admin_subject = new AdminSubject;
                $admin_subject->admin_id = $teacher->id;
                $admin_subject->subject_id = $subject['subject_id'];
                $admin_subject->save();

                $admin_subject->classes()->sync($subject['classes']);
            }
        }

        $teacher->load(['subjects' => function ($q) {
            $q->with('classes','subject');
        }]);

        $message = "Details updated successfully";

        return compact('teacher','message');
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'password' => ['required', 'string'],
        ]);


        $teacher = Admin::find(Auth::id());
        $teacher->password = $request->password;

        $teacher->save();

        $message = "Your password was updated successfully";

        return compact('teacher','message');
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



