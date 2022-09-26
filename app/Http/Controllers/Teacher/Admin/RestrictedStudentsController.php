<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

class RestrictedStudentsController extends Controller
{
    /**
     * Create a new restricted student.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => ['required', 'int', 'exists:students,id'],
        ]);

        $student = Student::find($request->id);

        abort_if(! $student, 404, 'Student not found');
        abort_if((bool) $student->deactivated_at, 400, "This student's access has already been restricted");

        $student->deactivate();

        return $this->sendSuccessResponse('Student access restricted successfully!', $student);
    }

    /**
     * Remove student from restricted students list.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $student = Student::find($id);

        abort_if(! $student, 404, 'Student not found');
        abort_if(is_null($student->deactivated_at), 400, 'This student already has access to examinations');

        $student->reactivate();

        return $this->sendSuccessResponse('Student access restored successfully', $student);
    }
}
