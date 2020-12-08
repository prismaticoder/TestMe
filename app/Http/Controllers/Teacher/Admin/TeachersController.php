<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Admin;
use App\AdminSubject;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Support\Facades\DB;

class TeachersController extends Controller
{
    /**
     * Get all teachers
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Admin::teacher()->get();

        return $this->sendSuccessResponse("Teachers retrieved successfully", $teachers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateTeacherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeacherRequest $request)
    {
        DB::beginTransaction();

        try {
            $teacher = Admin::create([
                'username' => $request->username,
                'password' => $request->password,
                'role_id' => Admin::ROLES['TEACHER']
            ]);

            foreach ($request->subjects as $subject) {
                $this->createSubjectWithClasses($teacher->id,$subject);
            }

            DB::commit();
            $teacher->load(['subjects' => function ($q) {
                $q->with('classes','subject');
            }]);

            return $this->sendSuccessResponse("User <{$teacher->username}> created successfully.", $teacher, 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return $this->sendErrorResponse("There was an error creating this teacher: {$e->getMessage()}");
        }
    }

    /**
     * Update a teacher's details
     *
     * @param  \App\Http\Requests\UpdateTeacherRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        $teacher = Admin::teacher()->where('id', $id)->first();

        abort_if(! $teacher, 404, "Teacher not found");

        DB::beginTransaction();

        try {
            $teacher->username = $request->username;
            $teacher->save();

            $teacher->subjects()->delete();

            foreach ($request->subjects as $subject) {
                $this->createSubjectWithClasses($teacher->id, $subject);
            }

            DB::commit();
            $teacher->load(['subjects' => function ($q) {
                $q->with('classes','subject');
            }]);

            return $this->sendSuccessResponse("Teacher details updated successfully", $teacher);
        } catch (\Throwable $e) {
            DB::rollback();
            return $this->sendErrorResponse("There was an error updating this teacher's details: {$e->getMessage()}");
        }
    }

    /**
     * Revoke user access for a teacher
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Admin::teacher()->where('id', $id)->first();

        abort_if(! $teacher, 404, "Teacher not found");

        $teacher->delete();
        return $this->sendSuccessResponse("Access revoked successfully for user <{$teacher->username}>");
    }

    private function createSubjectWithClasses(int $teacherId, array $subjectWithClasses): void
    {
        $adminSubject = AdminSubject::create([
            'admin_id' => $teacherId,
            'subject_id' => $subjectWithClasses['subject_id']
        ]);

        $adminSubject->classes()->sync($subjectWithClasses['classes']);
    }
}
