<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subject;

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
        return Subject::where('id',$subject_id)->first()->hasStarted;
    }


}

