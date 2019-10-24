<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Classes;
use App\Question;
use App\Option;

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
        $class = Classes::where('class',$class)->first();

        if ($class) {
            $students = $class->students;

            return view('admin.class-students')->with('students',$students);
        }

        return abort('404','Page does not exist');
    }

    public function getAllQuestions($subject) {
        $subject = Subject::where('alias',$subject)->first();

        if ($subject) {
            $questions = $subject->questions;
            $answers = $subject->answers;

            return view('admin.questions', compact('questions','answers'));
        }

        return abort('404','Page does not exist');
    }
}
