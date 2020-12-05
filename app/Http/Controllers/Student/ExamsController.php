<?php

namespace App\Http\Controllers\Student;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExamRequest;
use App\Http\Requests\UpdateExamRequest;

class ExamsController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Create a new exam
     *
     * @param  \App\Http\Requests\CreateExamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExamRequest $request)
    {
        abort_if($request->hours === 0 && $request->minutes === 0, 400, "Hour and minute cannot both have values as zero!");

        $exam = Exam::create($request->only('subject_id','class_id','base_score','hours','minutes','dates'));
        $exam->load('subject','class');

        session()->put('exam_id', $exam->id);

        return $this->sendSuccessResponse("Exam created successfully", $exam, 201);
    }

    /**
     * Update an existing exam
     *
     * @param  \App\Http\Requests\UpdateExamRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamRequest $request, $id)
    {
        $exam = Exam::find($id);

        abort_if($request->hours === 0 && $request->minutes === 0, 400, "Hour and minute cannot both have values as zero!");
        abort_if(! $exam, 404, "Exam not found");

        $exam->update($request->validated());

        $exam->load('subject','class');

        return $this->sendSuccessResponse("Exam settings updated successfully", $exam);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
