<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['class_id','subject_id','base_score','hours','minutes','date','hasStarted'];
    protected $appends = ['hasBeenWritten', 'questions'];

    //
    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function scopeStarted($query) {
        return $query->where('hasStarted',1);
    }

    public function scopeNotStarted($query) {
        return $query->where('hasStarted',0)->latest('updated_at');
    }

    public function scopeCanBeStarted($query) {
        return $query->notStarted()->where('date', date('Y-m-d'))->has('questions');
    }

    public function getHasBeenWrittenAttribute() {
        return count ($this->scores) > 0 ? true : false;
    }

    public function getQuestionsAttribute() {
        return $this->questions()->with('options')->get();
    }

    public function start() {
        $this->hasStarted = 1;
        $this->save();
    }

    public function end() {
        $this->hasStarted = 0;
        $this->save();
    }
}
