<?php

namespace App\Http\Controllers\Teacher;

use App\Classes;
use App\Exam;
use App\Http\Controllers\Controller;
use App\Subject;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        $currentExam = $exams->first();

        if ($request->query('exam_id')) {
            $currentExam = $exams->where('id', $request->query('exam_id'))->first();
        }

        $isLatestExam = json_encode($currentExam && ($currentExam->id === $exams[0]->id));
        $students = $this->getExamStudents($currentExam, $class);
        $classes = auth()->user()->isAdmin()
                    ? $subject->classes->sortBy('id')
                    : $subject->teacherSubjects()->where('teacher_id', auth()->id())->first()->classes->sortBy('id');

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

        $exam = Exam::withCount('questions')->where('id', $examId)->firstOrFail();
        $students = $this->getExamStudents($exam, $class);

        $data = compact('subject', 'class', 'exam', 'students');

        $pdf = PDF::loadView('teacher.pdf-result', $data);
        $fileName = sprintf('%s_%s_results.pdf', strtolower($subject->slug), strtolower($class->name));

        return $pdf->download($fileName);
    }

    /**
     * Get the students involved in a particular exam.
     *
     * @param \App\Exam|null $exam
     * @param \App\Classes $currentClass
     *
     * @return \Illuminate\Support\Collection
     */
    private function getExamStudents(?Exam $exam, Classes $currentClass): Collection
    {
        if (! $exam || $exam->students->isEmpty()) {
            return $currentClass->students;
        }

        $classThatTookExam = $exam->students->isNotEmpty() ? $exam->students->first()->class : $currentClass;

        return $classThatTookExam->students->merge($exam->students);
    }
}
