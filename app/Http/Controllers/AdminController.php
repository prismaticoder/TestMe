<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Classes;
use App\Question;
use App\Option;
use DB;

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

    public function getAllQuestions($subject,$class_id) {
        $subject = Subject::where('alias',$subject)->first();
        $subject_id = $subject['id'];

        if ($subject) {

            $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->get();
            // $options = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->options;

            return view('admin.questions', compact('questions'));
        }

        return abort('404','Page does not exist');
    }

    public function addQuestion(Request $request) {
        // $question = $request->input('question');
        // DB::transaction(function(Request $request) use() {
        //     Question::options()->create($request->only('optionA','optionB',)
        // });
    }

    public function updateQuestion($id) {

    }
}
