<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Question;
use App\Subject;
use App\Option;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
            // Session::put('scoreArray', []);
            // session('scoreArray',[]);
            $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->get();

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

    public function calculateScore(Request $request, Session $session) {
        $question_id = $request->question_id;
        $option_id = $request->option_id;

        $scoreArray = session('scoreArray');

        Log::info($option_id);

        // Log::info($request->session()->all());

        foreach (session('scoreArray') as $key => $array) {
            if ($array['question_id'] == $question_id) {
                unset(session('scoreArray')[$key]);
            }
        }

        $option = Option::where('id',$option_id)->get();

        Log::info($option);

        if ($option[0]->isCorrect) {
            session()->push('scoreArray',['question_id'=>$question_id,'answer'=>1]);
        }
        else {
            session()->push('scoreArray',['question_id'=>$question_id,'answer'=>0]);
        }

        Log::info($scoreArray);

        return response()->json('Option Saved');
    }
}
