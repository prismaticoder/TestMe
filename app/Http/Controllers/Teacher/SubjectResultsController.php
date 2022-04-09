<?php

namespace App\Http\Controllers\Teacher;

use App\Exam;
use App\Classes;
use App\Subject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SubjectResultsController extends Controller
{
    /**
     * Display the results of a subject.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Subject $subject, Classes $class)
    {
        abort_if(
            Gate::denies('access-class-subject', [$class->id, $subject->id]),
            401,
            'You are not authorized to view results of this subject'
        );

        $exams = Exam::belongsToClassSubject($class->id, $subject->id)
            ->withCount('questions')
            ->latest('updated_at')
            ->get();

        if ($request->query('exam_id')) {
            $selectedExam = $exams->where('id', $request->query('exam_id'))->first();
        }

        $currentExam = $selectedExam ?? ($exams[0] ?? null);
        $isLatestExam = json_encode($currentExam && ($currentExam->id === $exams[0]->id));
        $students = $currentExam->students ?? null;
        $classes = auth()->user()->isAdmin()
                    ? $subject->classes->sortBy('id')
                    : $subject->teacherSubjects()->where('admin_id', auth()->id())->first()->classes->sortBy('id');

        return view(
            'teacher.results',
            compact('subject', 'class', 'exams', 'currentExam', 'isLatestExam', 'students', 'classes')
        );
    }

    /**
     * Download a subject result.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Subject $subject, Classes $class, int $examId)
    {
        abort_if(
            Gate::denies('access-class-subject', [$class->id, $subject->id]),
            401,
            'You are not authorized to view results of this subject'
        );

        $exam = Exam::findOrFail($examId);
        $students = $exam->students;

        $data = compact('subject', 'class', 'exam', 'students');

        $pdf = PDF::loadView('teacher.pdf-result', $data);
        $fileName = sprintf('%s_%s_results.pdf', strtolower($subject->slug), strtolower($class->name));

        return $pdf->download($fileName);
    }
}
