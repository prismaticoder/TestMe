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

        return view('student.home',compact('exams'));
    }

    /**
     * Get single exam
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {

        //check if the subject has any exam to be hosted on the particular day
        $exam = Exam::started()
                    ->belongsToClassSubject(auth()->user()->class_id, $subject->id)
                    ->latest('updated_at')
                    ->first();

        abort_if(! $exam, 404, "Page not found");
        abort_if($exam->hasBeenWrittenByCurrentUser(), 403, "You have already written this examination");

        $hours = $exam->hours;
        $minutes = $exam->minutes;
        $questions = Question::where('exam_id',$exam->id)->with('options:id,question_id,body')->inRandomOrder(auth()->user()->seed)->get();

        session()->put('exam_id', $exam->id);

        return view('student.exam', compact('subject','exam','hours','minutes','questions'));
    }
}
