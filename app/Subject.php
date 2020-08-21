<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function options() {
        return $this->hasManyThrough(Option::class, Question::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'questions');
    }

    public function admins() {
        return $this->belongsToMany(Admin::class);
    }

    public function getStartedExam($class_id) {
        $today = date('Y-m-d');
        $exam = Exam::where('subject_id',$this->id)->where('class_id',$class_id)->where('date', $today)->where('hasStarted', 1)->first();

        return $exam ? $exam : null;
    }
}



