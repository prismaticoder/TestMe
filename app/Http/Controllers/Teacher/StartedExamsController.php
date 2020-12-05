<?php

namespace App\Http\Controllers\Teacher;

use App\Exam;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StartedExamsController extends Controller
{

    /**
     * Start a new exam
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        list($subjectId, $classId) = $request->only('subject_id','class_id');

        $exam = Exam::canBeStarted()->where('subject_id',$subjectId)->where('class_id',$classId)->with('subject','class')->firstOrFail();

        abort_if(! Gate::allows('view-subject-details', [$subjectId, $classId]), 403, "You are not authorized to start an exam for this class subject");

        $exam->start();

        return $this->sendSuccessResponse("Exam {$exam->subject->name} started successfully", $exam);
    }

    /**
     * End an already started exam
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = Exam::started()->where('id', $id)->with('subject','class')->firstOrFail();

        abort_if(! Gate::allows('view-subject-details', [$exam->subject_id, $exam->class_id]), 403, "You are not authorized to end an exam for this class subject");

        $exam->end();

        $this->sendSuccessResponse("Exam {$exam->subject->name} ended successfully", $exam);
    }
}
