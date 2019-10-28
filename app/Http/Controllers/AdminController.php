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
        $subjects = Subject::get();
        $classes = Classes::get();
        return view('admin.dashboard',compact('subjects','classes'));
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

            return view('admin.questions', compact('questions','subject','class_id'));
        }

        return abort('404','Page does not exist');
    }

    public function addQuestion(Request $request,$subject,$class_id) {
        $subject = Subject::where('alias',$subject)->first();

        if ($subject) {
            $subject_id = $subject->id;
            $question = $request->question;
            $options = $request->only(['optionA','optionB','optionC','optionD']);
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
        }

        return abort('404');
    }

    public function updateQuestion(Request $request,$subject,$class_id,$id) {
        $subject = Subject::where('alias',$subject)->first();
        if ($subject) {
            $question = $request->only('question');
            $options = $request->only('optionA','optionB','optionC','optionD');
            $correctAnswer = $request->only('correct');

            DB::transaction(function() use($question,$options,$correctAnswer,$id) {
                Question::find($id)->update([
                    'question' => $question,
                ]);
                foreach ($options as $key=>$option) {
                    Question::find($id)->options()->update([
                        'body' => $option,
                        'isCorrect' => ($correctAnswer == $key)?1:0
                    ]);
                }
            });

            return redirect()->back();
        }

        return abort('404');

    }
}
