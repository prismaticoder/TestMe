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
        $this->middleware('auth:admins');
    }

    public function dashboard() {
        return view('admin.dashboard');
    }

    public function getClassStudents($class) {
        $class = Classes::where('class',$class);

        $students = $class->students;

        return view('admin.class-students')->with('students',$students);
    }

    public function getAllQuestions($subject) {
        $subject = Subject::where('alias',$subject)->get();

        $questions = $subject->questions;
        $answers = $subject->answers;

        return view('admin.questions', compact('questions','answers'));
    }
}
