<?php

namespace App\Http\Controllers\Student;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Question;
use App\Subject;

class ExamsController extends Controller
{
    /**
     * Get All available exams that a student can partake in
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = auth()->user()->getAvailableExams();

        return view('home',compact('exams'));
    }

    /**
     * Get single exam
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($subjectAlias)
    {
        $subject = Subject::findThroughAlias($subjectAlias);

        //check if the subject has any exam to be hosted on the particular day
        $exam = Exam::started()
                    ->belongsToClassSubject(auth()->user()->class_id, $subject->id)
                    ->whereDoesntHave('submissions', function($query) {
                        $query->where('student_id', auth()->id());
                    })
                    ->latest('updated_at')
                    ->first();

        abort_if(! $exam, 404, "Page not found");

        $hours = $exam->hours;
        $minutes = $exam->minutes;

        $questions = Question::where('exam_id',$exam->id)->with('options:id,question_id,body')->inRandomOrder(auth()->user()->seed)->get();

        session()->put('exam_id', $exam->id);

        return view('exam', compact('questions','user','subject','hours','minutes','exam'));
    }
}
