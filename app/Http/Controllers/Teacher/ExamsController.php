<?php

namespace App\Http\Controllers\Teacher;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExamRequest;
use App\Http\Requests\UpdateExamRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ExamsController extends Controller
{
    /**
     * Create a new exam.
     *
     * @param \App\Http\Requests\CreateExamRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExamRequest $request)
    {
        abort_if($request->hours === 0 && $request->minutes === 0, 400, 'Hour and minute cannot both have values as zero!');

        $exam = Exam::create($request->only('subject_id', 'class_id', 'aggregate_score', 'hours', 'minutes', 'date'));

        if (isset($request->from_exam_id)) {
            $this->importExamQuestions($request->from_exam_id, $exam->id, $request->number_to_import);
        }

        $exam->load('subject', 'class');

        session()->put('exam_id', $exam->id);

        return $this->sendSuccessResponse('Exam created successfully', $exam, 201);
    }

    /**
     * Create a new exam.
     *
     * @param \App\Http\Requests\CreateExamRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request)
    {
        $request->validate([
            'from_exam_id' => ['required', 'int', 'exists:exams,id', 'not_in:'.session()->get('exam_id')],
            'number' => ['required', 'int', 'min:1', 'max:60'],
        ]);

        $currentExam = Exam::where('id', session()->get('exam_id'))->with('subject', 'class')->first();

        abort_if(! is_null($currentExam->duplicated_from), 403, 'You cannot import questions more than once for this exam.');

        $examToDuplicate = Exam::find($request->from_exam_id);

        abort_if(Gate::denies('access-class-subject', [$examToDuplicate->class_id, $examToDuplicate->subject_id]), 403, 'You do not have the appropriate permissions to duplicate this examination.');

        $numberOfQuestionsToImport = $request->number;

        $this->importExamQuestions($request->from_exam_id, session()->get('exam_id'), $numberOfQuestionsToImport);

        return $this->sendSuccessResponse('Questions imported successfully', $currentExam->refresh());
    }

    /**
     * Update an existing exam.
     *
     * @param \App\Http\Requests\UpdateExamRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamRequest $request, int $id)
    {
        $exam = Exam::find($id);

        abort_if($request->hours === 0 && $request->minutes === 0, 400, 'Hour and minute cannot both have values as zero!');
        abort_if(! $exam, 404, 'Exam not found');

        $exam->update($request->validated());

        $exam->load('subject', 'class');

        return $this->sendSuccessResponse('Exam settings updated successfully', $exam);
    }

    private function importExamQuestions(int $from, int $to, int $numberToImport): void
    {
        $fromExam = Exam::find($from);
        $toExam = Exam::find($to);

        $questions = $fromExam->questions()->with('options')->inRandomOrder()->limit($numberToImport)->get();

        DB::transaction(function () use ($questions, $fromExam, $toExam) {
            foreach ($questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->exam_id = $toExam->id;
                $newQuestion->save();

                foreach ($question->options as $option) {
                    $newOption = $option->replicate();
                    $newOption->question_id = $newQuestion->id;
                    $newOption->save();
                }
            }

            $toExam->update(['duplicated_from' => $fromExam->id,
            ]);
        });
    }
}
