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

        return view('admin.teacher-subject', compact('classes','type','subjects'));
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
            'name' => ['required', 'string', 'unique:subjects,name'],
            'classes' => ['required', 'array'],
            'classes.*' => ['required', 'exists:classes,id']
        ]);

        $subject = Subject::create([
            'name' => $request->name,
            'alias' => Str::slug($request->name)
        ]);
        $subject->classes()->sync($request->classes);

        $subject->load('classes');

        return $this->sendSuccessResponse("New subject \"{$subject->name}\" created successfully", $subject, 201);
    }

    /**
     * Update a subject's details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:subjects,name,'.$id.',id'],
            'classes' => ['required', 'array'],
            'classes.*' => ['required', 'exists:classes,id']
        ]);

        $subject = Subject::find($id);

        abort_if(! $subject, 404, "Subject not found");

        $subject->update([
            'name' => $request->name,
            'alias' => Str::slug($request->name)
        ]);
        $subject->classes()->sync($request->classes);

        $subject->load('classes');

        return $this->sendSuccessResponse("Subject details updated successfully", $subject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);

        abort_if(! $subject, 404, "Subject not found");

        $subject->delete();
        return $this->sendSuccessResponse("Subject {$subject->name} deleted successfully");
    }
}
