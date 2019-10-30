<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Classes;
use App\Question;
use App\Option;
use DB;
use Illuminate\Support\Facades\Log;

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
        $subjects = Subject::get();
        $classes = Classes::get();
        return view('admin.dashboard',compact('subjects','classes'));
    }

    public function getClassStudents($class) {
        $class = Classes::where('class',$class)->first();

        if ($class) {
            $students = $class->students;

            return view('admin.class-students', compact('students','class'));
        }

        return abort('404','Page does not exist');
    }

    public function getAllQuestions($subject,$class_id) {
        $subject = Subject::where('alias',$subject)->first();
        $subject_id = $subject['id'];
        $classes = Classes::all();

        if ($subject) {

            $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->get();
            // $options = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->options;

            return view('admin.questions', compact('questions','subject','class_id','classes'));
        }

        return abort('404','Page does not exist');
    }

    public function addQuestion(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;
        $question = $request->question;
        $options = $request->options;
        $correctAnswer = $request->correct;



        DB::transaction(function() use($class_id,$subject_id,$question,$options,$correctAnswer) {
            Question::create([
                'subject_id' => $subject_id,
                'class_id' => $class_id,
                'question' => $question,
            ]);
            foreach ($options as $key=>$option) {
                Question::orderBy('created_at','desc')->first()->options()->create([
                    'body' => $option,
                    'isCorrect' => ($correctAnswer == $key)?1:0
                ]);
            }
        });

        $newQuestion = Question::orderBy('created_at','desc')->first();
        $count = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->count();

        Log::info($newQuestion);

        return response()->json(['question'=>$newQuestion,'count'=>$count]);
    }

    public function updateQuestion(Request $request, $id) {
        $question = $request->question;
        $options = $request->options;
        $correctAnswer = $request->correct;
        $questionDB = Question::where('id',$id)->first();

        Log::info($options);

        DB::transaction(function() use($question,$options,$correctAnswer,$questionDB) {
            $questionDB->update([
                'question' => $question,
            ]);
            foreach ($options as $key => $option) {
                $optionFind = Option::find($option['id']);
                $optionFind->body = $option['value'];
                $optionFind->isCorrect = ($correctAnswer == $key)?1:0;
                $optionFind->save();
            }
        });

        return response()->json('Question Updated Succesfully');

    }

    public function findOneQuestion(Request $request, $id) {
        $question = Question::where('id',$id)->with('options')->get();

        return response()->json($question[0]);
    }
}
