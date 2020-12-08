<?php

namespace App\Http\Middleware;

use App\Exam;
use Closure;

class CheckExamStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $exam_id = session()->get('exam_id');

        $exam = Exam::find($exam_id);

        abort_if($exam && $exam->has_started, 403, "Error: this action cannot be performed for an examination in progress");

        return $next($request);
    }
}
