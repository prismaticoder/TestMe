<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mark;

class Classes extends Model
{
    protected $table = 'classes';

    public function students() {
        return $this->hasMany(User::class, 'class_id');
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class, 'questions');
    }

    public function hasStarted($subject_id) {
        //check if a specific class has started exams for a specific subject
        $mark = Mark::where('subject_id',$subject_id)->where('class_id', $this->id)->first();
        return  $mark ? $mark->hasStarted : 0;
    }

    public function checkParams($subject_id) {
        //check if a specific class has started exams for a specific subject
        $mark = Mark::where('subject_id',$subject_id)->where('class_id', $this->id)->first();

        if (!$mark) {
            return 0;
        }
        else {
            //only return true for subjects that have questions
            return count($mark->subject->questions()->get()) > 0 ? 1 : 0;
        }
    }


}

