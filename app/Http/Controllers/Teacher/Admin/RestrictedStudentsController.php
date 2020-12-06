<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

class RestrictedStudentsController extends Controller
{
    /**
     * Create a new restricted student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => ['required', 'int', 'exists:students,id']
        ]);

        $student = Student::find($request->id);
        $student->delete();

        return $this->sendSuccessResponse("Student access restricted");
    }

    /**
     * Remove student from restricted students list
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $student = Student::onlyTrashed()->where('id',$id)->first();

        abort_if(! $student, 404, "Student not found");

        $student->restore();

        $this->sendSuccessResponse("Student access restored successfully");
    }
}
