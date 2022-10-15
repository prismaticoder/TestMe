<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Classes;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Subject;
use App\Teacher;
use App\TeacherSubject;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TeachersController extends Controller
{
    /**
     * Get all teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::with('classes')->get();
        $classes = Classes::all();
        $type = 'teachers';
        $teachers = Teacher::notAdmin()->orderBy('username')->with(['subjects' => function ($q) {
            $q->with('classes', 'subject');
        }])->get();

        return view('teacher.admin.teacher-subject', compact('subjects', 'classes', 'type', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CreateTeacherRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeacherRequest $request)
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::create($request->only('title', 'firstname', 'lastname', 'username', 'password'));

            foreach ($request->subjects as $subject) {
                $this->createSubjectWithClasses($teacher->id, $subject);
            }

            DB::commit();
            $teacher->load(['subjects' => function ($q) {
                $q->with('classes', 'subject');
            }]);

            return $this->sendSuccessResponse("User <b>{$teacher->username}</b> created successfully.", $teacher, 201);
        } catch (\Throwable $e) {
            DB::rollback();

            return $this->sendErrorResponse("There was an error creating this teacher: {$e->getMessage()}");
        }
    }

    /**
     * Update a teacher's details.
     *
     * @param \App\Http\Requests\UpdateTeacherRequest $request
     * @param \App\Teacher $teacher
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        abort_if($teacher->isAdmin(), 404, 'Teacher not found');

        DB::beginTransaction();

        try {
            $subjectIds = Arr::pluck($request->subjects, 'subject_id');

            $teacher->subjects()->whereNotIn('subject_id', $subjectIds)->delete();

            foreach ($request->subjects as $subject) {
                $this->createSubjectWithClasses($teacher->id, $subject);
            }

            DB::commit();
            $teacher->load(['subjects' => function ($q) {
                $q->with('classes', 'subject');
            }]);

            return $this->sendSuccessResponse('Teacher details updated successfully', $teacher);
        } catch (\Throwable $e) {
            DB::rollback();

            return $this->sendErrorResponse("There was an error updating this teacher's details: {$e->getMessage()}");
        }
    }

    /**
     * Revoke user access for a teacher.
     *
     * @param \App\Teacher $teacher
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        abort_if($teacher->isAdmin(), 404, 'Teacher not found');

        $teacher->delete();

        return $this->sendSuccessResponse("Access revoked successfully for user <b>{$teacher->username}</b>");
    }

    private function createSubjectWithClasses(int $teacherId, array $subjectWithClasses): void
    {
        $teacherSubject = TeacherSubject::firstOrCreate([
            'teacher_id' => $teacherId,
            'subject_id' => $subjectWithClasses['subject_id'],
        ]);

        $teacherSubject->classes()->sync($subjectWithClasses['classes']);
    }
}
