<?php

namespace App\Http\Controllers;

use App\Mark;
use Illuminate\Http\Request;
use Auth;
use App\Question;
use App\Subject;
use App\Option;
use App\Score;
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

    public function index() {
        $subjects = Subject::all();
        return view('home',compact('subjects'));
    }

    public function getExamQuestions(\Request $request, $subject) {

        $checkHost = Subject::where('alias',$subject)->first()->isHosted;

        // If and Only if CheckHost applies should a user proceed further, else, return 401 - Not Authorized

        if ($checkHost) {
            $user = Auth::user();
            $class_id = $user->class_id;

            $name = $user->firstname . ' ' . $user->lastname;

            $subject = Subject::where('alias',$subject)->first();
            $mark = Mark::where('subject_id',$subject->id)->where('class_id',$class_id)->first();
            $hours = $mark->hours;
            $minutes = $mark->minutes;

            // $seed = rand(0000,9999);
            // Session::put('seed', $seed);

            if ($subject) {
                $subject_id = $subject->id;
                $seed = Auth::user()->code;
                // Session::put('scoreArray', []);
                // session('scoreArray',[]);
                $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->inRandomOrder($seed)->get();

                return view('exam',compact('questions','name','user','subject','hours','minutes'));
            }

            return abort('404');
        }

        return abort('401');



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


    //Function to calculate student scores
    public function calculateScore(Request $request, Session $session) {
        $question_id = $request->question_id;
        $option_id = $request->option_id;

        $scoreArray = session()->pull('scoreArray');

        Log::info($option_id);

        // Log::info($request->session()->all());

        foreach ($scoreArray as $key => $array) {
            if ($question_id == $array['question_id']) {
                // unset($scoreArray[$key]);
                // array_splice(session('scoreArray'),$key,1);
                // $array['answer'] = 0;
                unset($scoreArray[$key]);
            }
        }

        $option = Option::where('id',$option_id)->get();

        Log::info($option);

        if ($option[0]->isCorrect) {
            array_push($scoreArray,['question_id'=>$question_id,'answer'=>1]);
        }
        else {
            array_push($scoreArray,['question_id'=>$question_id,'answer'=>0]);
        }

        session()->put('scoreArray',$scoreArray);

        Log::info(session('scoreArray'));

        $ara = [];

        foreach (session('scoreArray') as $key => $arr) {
            array_push($ara,$arr['answer']);
        }

        $score = array_sum($ara);

        return response()->json($score);
    }

    public function submitQuestion(Request $request) {
        $subject = $request->subject;
        $class_id = $request->class_id;
        $count = $request->count;

        $subject = Subject::where('alias',$subject)->first();
        $mark = (Mark::where('subject_id',$subject->id)->where('class_id',$class_id)->first())?Mark::where('subject_id',$subject->id)->where('class_id',$class_id)->first()->mark:50;

        $divisor = ($mark)/$count;

        $ara = [];

        foreach (session('scoreArray') as $key => $arr) {
            array_push($ara,$arr['answer']);
        }

        $score = array_sum($ara);

        $scoreTable = new Score;
        $scoreTable->subject_id = $subject->id;
        $scoreTable->class_id = $class_id;
        $scoreTable->user_id = Auth::user()->id;
        $scoreTable->original = $score;
        $scoreTable->score = $score * $divisor;
        $scoreTable->save();

        return response()->json('success');
    }

    public function submitSuccess() {
        return view('submit-success');
    }
}
