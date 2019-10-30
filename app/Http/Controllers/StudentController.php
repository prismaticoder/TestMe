<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Question;
use App\Subject;
use Session;

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

    public function getExamQuestions(\Request $request, $subject) {

        $checkHost = Subject::where('alias',$subject)->first()->isHosted;

        // If and Only if CheckHost applies should a user proceed further, else, return 401 - Not Authorized


        $user = Auth::user();
        $class_id = $user->class_id;

        $name = $user->firstname . ' ' . $user->lastname;

        $subject = Subject::where('alias',$subject)->first();

        // $seed = rand(0000,9999);
        // Session::put('seed', $seed);

        if ($subject) {
            $subject_id = $subject->id;
            $seed = Auth::user()->code;
            $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->inRandomOrder($seed)->paginate(1);

            return view('exam',compact('questions','name','user','subject'));
        }

        return abort('404');

    }

    public function getAjaxQuestions(Request $request) {
        $id = $request->question_id;
        $subject = $request->subject;
        $class_id = $request->class;
        $subject = Subject::where('alias',$subject)->first();
        $subject_id = $subject['id'];

        if ($subject) {
            $seed = Auth::user()->code;
            $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->inRandomOrder($seed)->get();
            // $options = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->options;
            $question = $questions[$id-1];

            return response()->json($question);
        }

        return abort('404');
    }
}
