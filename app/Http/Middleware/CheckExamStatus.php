<?php

namespace App\Http\Middleware;

use App\Exam;
use Closure;
use Illuminate\Support\Facades\Session;

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
        $exam_id = Session::get('exam_id');

        $exam = Exam::find($exam_id);

        if ($exam) {
            if ($exam->hasStarted) {
                return abort(403, "Error: this action cannot be performed for an examination in progress");
            }

            else {
                return $next($request);
            }
        }

        else {
            return $next($request);
        }
    }
}
