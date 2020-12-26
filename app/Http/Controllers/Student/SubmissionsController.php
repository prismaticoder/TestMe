<?php

namespace App\Http\Controllers\Student;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;

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
            'choices' => ['required', 'json']
        ]);

        $examId = session()->get('exam_id');

        abort_if(auth()->user()->submissions->where('exam_id', $examId)->exists(), 403, "You have already made a submission for this exam");

        $choices = json_decode($request->choices);
        $scores = $this->computeExamScore($choices);

        auth()->user()->submissions()->create([
            'exam_id' => $examId,
            'actual_score' => $scores['actual'],
            'computed_score' => $scores['computed']
        ]);

        return $this->sendSuccessResponse("Submission successful!");
    }

    private function computeExamScore(array $studentResponses): array
    {
        $examId = session()->get('exam_id');
        $questions = Question::where(compact('exam_id'))->with('options')->inRandomOrder(auth()->user()->seed)->get();
        $baseScore = Exam::find($examId)->base_score;
        $averagePoint = $baseScore/$questions->count();

        $score = 0;
        foreach ($studentResponses as $response) {
            $correspondingQuestion = $questions[$response->question - 1];
            if (! is_null($response->choice)) {
                $score += (int) $correspondingQuestion->options[$response->choice]->is_correct;
            }
        }

        return array(
            'actual' => $score,
            'computed' => $score * $averagePoint
        );
    }
}
