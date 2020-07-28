<?php

namespace App\Http\Middleware;

use App\Mark;
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
        $subject_id = Session::get('subject_id');
        $class_id = Session::get('class_id');

        $subject_param = Mark::where('subject_id',$subject_id)->where('class_id',$class_id)->first();

        if ($subject_param) {
            if ($subject_param->hasStarted) {
                abort(403, "Error: this action cannot be performed for an examination in progress");
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
