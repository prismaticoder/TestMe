<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Question;
use App\Subject;

class StudentController extends Controller
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

    public function getExamQuestions($subject) {
        $user = Auth::user();
        $class_id = $user->class_id;

        $name = $user->firstname . ' ' . $user->lastname;

        $subject = Subject::where('alias',$subject)->first();

        if ($subject) {
            $subject_id = $subject->id;
            $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->inRandomOrder()->get();

            return view('exam',compact('questions','name','user'));
        }

        return abort('404');

    }
}
