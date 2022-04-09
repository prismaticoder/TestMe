<?php

namespace App\Http\Controllers\Teacher;

use App\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreateExamRequest;
use App\Http\Requests\UpdateExamRequest;

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
            'number' => ['required', 'int', 'min:1', 'max:30'],
        ]);

        $examToDuplicate = Exam::find($request->from_exam_id);
        abort_if(Gate::denies('access-class-subject', [$examToDuplicate->subject_id, $examToDuplicate->class_id]), 403, 'You do not have the appropriate permissions to duplicate this examination.');

        $numberOfQuestionsToImport = $request->number;

        $questions = $examToDuplicate->questions()->with('options')->inRandomOrder()->get();
        $questions->take($numberOfQuestionsToImport);

        $this->importIntoCurrentExam($questions);

        $exam = Exam::where('id', session()->get('exam_id'))->with('subject', 'class')->first();
        $exam->update([
            'duplicated_from' => $request->from_exam_id,
        ]);

        return $this->sendSuccessResponse('Questions imported successfully', $exam);
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

        abort_if(Gate::denies('access-class-subject', [$fromExam->class_id, $fromExam->subject_id]), 403, 'You do not have the appropriate permissions to import questions from this examination.');

        $questions = $fromExam->questions()->with('options')->inRandomOrder()->get();
        $questions->take($numberToImport);

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

            $toExam->update([
                'duplicated_from' => $fromExam->id,
            ]);
        });
    }
}
