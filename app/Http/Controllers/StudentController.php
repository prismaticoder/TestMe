<?php

namespace App\Http\Controllers;

use App\Exam;
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
        $exams = Auth::user()->getAllStartedExams();

        return view('home',compact('exams'));
    }

    public function getExamQuestions(\Request $request, $subject) {
        $subject = Subject::where('alias',$subject)->first();
        $user = Auth::user();
        $class_id = $user->class_id;

        //check if the subject has any exam to be hosted on the particular day
        $exam = $subject->getStartedExam($class_id);

        if ($exam) {

            $hours = $exam->hours;
            $minutes = $exam->minutes;
            $subject_id = $subject->id;
            $seed = Auth::user()->code;

            $questions = Question::where('exam_id',$exam->id)->with('options:id,question_id,body')->inRandomOrder($seed)->get();

            Session::put('exam_id', $exam->id);

            return view('exam',compact('questions','user','subject','hours','minutes','exam'));
        }

        return abort('404');
    }

    public function submitExam(Request $request) {
        $choices = json_decode($request->choices);
        $exam_id = Session::get('exam_id');

        //first mark all the answers
        $seed = Auth::user()->code;

        $questions = Question::where('exam_id',$exam_id)->with('options')->inRandomOrder($seed)->get();

        $base_score = Exam::find($exam_id)->base_score;

        $divisor = ($base_score)/count($questions);
        //Now mark in Accordance
        $score = 0;
        foreach ($choices as $choice) {
            $corresponding_question = $questions[$choice->question - 1];

            if ($choice->choice) {
                //check if the answer is correct
                if ($corresponding_question->options[$choice->choice]->isCorrect) {
                    $score += 1;
                }
            }

            else {
                continue;
            }
        }

        $scoreTable = new Score;
        $scoreTable->exam_id = $exam_id;
        $scoreTable->user_id = Auth::user()->id;
        $scoreTable->actual_score = $score;
        $scoreTable->computed_score = $score * $divisor;
        $scoreTable->save();

        return response()->json('submission successful');

    }

    public function submitSuccess() {
        return view('submit-success');
    }
}
