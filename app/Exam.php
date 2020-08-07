<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['class_id','subject_id','base_score','hours','minutes','date','hasStarted'];
    protected $appends = ['hasBeenWritten', 'questions'];

    //
    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function getHasBeenWrittenAttribute() {
        return count ($this->scores) > 0 ? true : false;
    }

    public function getQuestionsAttribute() {
        return $this->questions()->with('options')->get();
    }
}
