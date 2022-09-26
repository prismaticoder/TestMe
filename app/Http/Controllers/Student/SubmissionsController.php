<?php

namespace App\Http\Controllers\Student;

use App\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmissionsController extends Controller
{
    /**
     * Create a new submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'choices' => ['required', 'array'],
            'choices.*' => ['required', 'array', 'size:2'],
            'choices.*.question' => ['required', 'int', 'not_in:0'],
            'choices.*.choice' => ['nullable', 'int', 'digits_between:0,3']
        ]);

        $examId = session()->get('exam_id');

        abort_if(
            auth()->user()->submissions()->where('exam_id', $examId)->exists(),
            403,
            'You have already made a submission for this exam'
        );

        $scores = $this->computeExamScore($request->choices);

        auth()->user()->submissions()->create([
            'exam_id' => $examId,
            'actual_score' => $scores['actual'],
            'computed_score' => $scores['computed']
        ]);

        return $this->sendSuccessResponse('Submission successful!');
    }

    private function computeExamScore(array $studentResponses): array
    {
        $examId = session()->get('exam_id');
        $exam = Exam::findOrFail($examId);
        $questions = $exam->questions()->with('options')->inRandomOrder(auth()->user()->seed)->get();

        $score = collect($studentResponses)->reduce(function ($currentScore, $response) use ($questions) {
            if (is_null($response['choice'])) {
                return $currentScore;
            }

            $correspondingQuestion = $questions[$response['question'] - 1];
            $chosenOption = $correspondingQuestion->options[$response['choice']];

            return $currentScore + (int)$chosenOption->is_correct ?? 0;
        }, 0);

        return [
            'actual' => $score,
            'computed' => $score * $exam->average_point
        ];
    }
}
