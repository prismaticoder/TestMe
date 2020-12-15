<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Classes;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class StudentsController extends Controller
{
    /**
     * Get all students with classes
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
     * Create a new student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2'],
            'class_id' => ['required', 'exists:classes,id']
        ]);

        $examination_number = Student::generateExaminationNumber();

        $student = Student::create(array_merge($validated, compact('examination_number')));

        return $this->sendSuccessResponse("Student added successfully", $student, 201);
    }

    /**
     * Create multiple students from file upload
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMany(Request $request)
    {
        $request->validate([
            'class_id' => ['required', 'int', 'exists:classes,id'],
            'students' => ['required', 'file', 'mimes:xlsx,xls,csv']
        ]);

        try {
            Excel::import(new StudentsImport($request->class_id), $request->students);

            $students = Student::where('class_id',$request->class_id)->get();
            return $this->sendSuccessResponse("Students added successfully", $students, 201);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            return $this->sendErrorResponse($failures[0]->errors()[0], 422);

       } catch (Throwable $e) {
            return $this->sendErrorResponse("There was an error uploading this file: {$e->getMessage()}");
       }
    }

    /**
     * Update a student's details
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2']
        ]);

        $student = Student::find($id);

        abort_if(! $student, 404, "Student not found");

        $student->update($validated);

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
