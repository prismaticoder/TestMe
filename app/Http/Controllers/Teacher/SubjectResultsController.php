<?php

namespace App\Http\Controllers\Teacher;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Student;
use App\Subject;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubjectResultsController extends Controller
{
    /**
     * Display the results of a subject
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $subjectAlias, int $classId)
    {
        $subject = Subject::findThroughAlias($subjectAlias);
        $currentClass = $subject->classes()->where('class_id', $classId)->firstOrFail();

        abort_if(Gate::denies('access-class-subject', [$currentClass->id, $subject->id]), 401, "You are not authorized to view results of this subject");

        $exams =  Exam::belongsToClassSubject($classId, $subject->id)->withCount('questions')->latest('updated_at')->get();

        if ($request->query('exam_id')) {
            $selectedExam = $exams->where('id', $request->query('exam_id'))->withCount('questions')->first();
        }

        $currentExam = $selectedExam ?? ($exams[0] ?? null);
        $isLatestExam = json_encode($currentExam && ($currentExam->id === $exams[0]->id));
        $students = $currentExam->students ?? null;
        $classes = auth()->user()->isAdmin()
                        ? $subject->classes->sortBy('id')
                        : $subject->teacherSubjects()->where('admin_id', auth()->id())->first()->classes->sortBy('id');

        return view('teacher.results',compact('subject','currentClass','exams','currentExam','isLatestExam','students','classes'));
    }

    /**
     * Download a subject result
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(string $subjectAlias, int $classId, int $examId)
    {
        $subject = Subject::findThroughAlias($subjectAlias);
        $currentClass = $subject->classes()->where('class_id', $classId)->firstOrFail();

        abort_if(Gate::denies('access-class-subject', [$currentClass->id, $subject->id]), 401, "You are not authorized to view results of this subject");

        $exam = Exam::find($examId);

        abort_if(! $exam, 404, "Exam not found");

        $students = $exam->students;

        $data = compact('subject','currentClass','exam','students');

        $pdf = PDF::loadView('teacher.pdf-result',$data);

        return $pdf->download(strtolower($subject->alias).'_'.strtolower($currentClass->name).'_results.pdf');
    }
}
