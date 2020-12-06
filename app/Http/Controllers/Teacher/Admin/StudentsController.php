<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Classes;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classes::with(['students' => function ($q) {
            $q->withTrashed()->orderBy('lastname');
          }])->get();

        return view('admin.class-students', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2'],
            'class_id' => ['required', 'exists:classes,id']
        ]);

        $examination_number = Student::generateExaminationNumber();

        $student = Student::create(array_merge($request->validated(), compact('examination_number')));

        $this->sendSuccessResponse("Student added successfully", $student, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2']
        ]);

        $student = Student::find($id);

        abort_if(! $student, 404, "Student not found");

        $student->update($request->validated());

        return $this->sendSuccessResponse("Student details updated successfully", $student);
    }

    /**
     * Delete a student from the system
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $student = Student::withTrashed()->find($id);

        abort_if(! $student, 404, "Student not found");

        $student->forceDelete();

        return $this->sendSuccessResponse("Student deleted successfully");
    }
}
