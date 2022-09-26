<?php

namespace App\Http\Controllers\Teacher\Admin;

use App\Classes;
use App\Http\Controllers\Controller;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classes::all();
        $type = 'subjects';
        $subjects = Subject::with('classes')->orderBy('name')->get();

        return view('teacher.admin.teacher-subject', compact('classes', 'type', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => ['required', 'string', 'unique:subjects,name'],
            'subject_code' => ['required', 'string', 'size:3', 'alpha', 'unique:subjects,code'],
            'classes' => ['required', 'array'],
            'classes.*' => ['required', 'exists:classes,id'],
        ]);

        $slug = $this->generateUniqueSlug($request);

        $subject = Subject::create([
            'name' => $request->subject_name,
            'code' => $request->subject_code,
            'slug' => $slug,
        ]);
        $subject->classes()->sync($request->classes);

        $subject->load('classes');

        return $this->sendSuccessResponse("New subject \"{$subject->name}\" created successfully", $subject, 201);
    }

    /**
     * Update a subject's details.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Subject $subject
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_name' => ['required', 'string', 'unique:subjects,name,'.$subject->id.',id'],
            'subject_code' => ['required', 'string', 'size:3', 'alpha', 'unique:subjects,code,'.$subject->id.',id'],
            'classes' => ['required', 'array'],
            'classes.*' => ['required', 'exists:classes,id'],
        ]);

        $slug = $this->generateUniqueSlug($request);

        $subject->update([
            'name' => $request->subject_name,
            'code' => $request->subject_code,
            'slug' => $slug,
        ]);
        $subject->classes()->sync($request->classes);

        $subject->load('classes');

        return $this->sendSuccessResponse('Subject details updated successfully', $subject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Subject $subject
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        if ($subject->exams()->exists()) {
            return $this->sendErrorResponse('This subject cannot be deleted because it has exams associated with it.');
        }

        $subject->delete();

        return $this->sendSuccessResponse("Subject {$subject->name} deleted successfully");
    }

    private function generateUniqueSlug(Request $request): string
    {
        $slug = Str::slug($request->subject_name);
        if (Subject::where('slug', $slug)->exists()) {
            $slug .= '-'.strtolower($request->subject_code);
        }

        return $slug;
    }
}
