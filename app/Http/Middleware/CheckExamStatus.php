<?php

namespace App\Http\Middleware;

use App\Exam;
use Closure;

class CheckExamStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $exam_id = session()->get('exam_id');

        $exam = Exam::findOrFail($exam_id);

        abort_if($exam->has_started, 403, 'Error: this action cannot be performed for an examination in progress.');
        abort_if($exam->hasBeenWritten, 403, 'Error: this action cannot be performed for an examination that already has a submission.');

        return $next($request);
    }
}
