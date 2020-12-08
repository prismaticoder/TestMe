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

        abort_if(Gate::denies('view-subject-details', [$subject->id, $classId]), 401, "You are not authorized to view results of this subject");

        $exams =  Exam::belongsToClassSubject($classId, $subject->id)->latest('updated_at')->orderBy('date','desc')->get();

        if ($request->query('exam_id')) {
            $selectedExam = $exams->where('id', $request->query('exam_id'))->first();
        }

        $currentExam = $selectedExam ?? ($exams[0] ?? null);
        $isLatestExam = (bool) ($currentExam && ($currentExam->id === $exams[0]->id));
        $students = Student::orderBy('lastname')
                            ->where('class_id', $classId)
                            ->with(['submissions' => function($query) use($currentExam) {
                                $query->where('exam_id', $currentExam->id ?? null);
                            }])
                            ->get();
        $classes = auth()->user()->isSuperAdmin()
                        ? $subject->classes
                        : $subject->adminSubjects()->where('admin_id', auth()->id())->first()->classes;

        return view('admin.main-result',compact('subject','currentClass','exams','currentExam','isLatestExam','students','classes'));
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

        abort_if(Gate::denies('view-subject-details', [$subject->id, $classId]), 401, "You are not authorized to view results of this subject");

        $exam = Exam::find($examId);

        abort_if(! $exam, 404, "Exam not found");

        $students = Student::orderBy('lastname')
                            ->where('class_id', $classId)
                            ->with(['submissions' => function($query) use($exam) {
                                $query->where('exam_id', $exam->id);
                            }])
                            ->get();

        $data = compact('subject','currentClass','exam','students');

        $pdf = PDF::loadView('admin.pdf-result',$data);

        return $pdf->download(strtolower($subject->alias).'_'.strtolower($currentClass->name).'_results.pdf');
    }
}
