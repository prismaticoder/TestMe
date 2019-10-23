<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getClassStudents($class) {
        $class = Classes::where('class',$class);

        $students = $class->students;

        return view('admin.class-students')->with('students',$students);
    }
}
