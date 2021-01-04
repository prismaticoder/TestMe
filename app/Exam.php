<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Exam extends Model
{
    protected $fillable = ['class_id','subject_id','base_score','hours','minutes','date','has_started'];
    protected $appends = ['hasBeenWritten', 'questions'];

    protected $dates = [
        'started_at'
    ];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($exam) {
            $exam->created_by = auth()->id();
        });
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'submissions')->withPivot('actual_score','computed_score');
    }

    public function submissions() {
        return $this->hasMany(Submission::class);
    }

    public function scopeStarted($query) {
        return $query->where('has_started',1);
    }

    public function scopeStartedByCurrentUser($query) {
        return $query->when(auth()->user()->isTeacher(), function($query) {
            return $query->whereIn('subject_id', Arr::pluck(auth()->user()->subjects, 'subject_id'));
        })->where('has_started',1);
    }

    public function scopeNotStarted($query) {
        return $query->where('has_started',0)->latest('updated_at');
    }

    public function scopeCanBeStarted($query) {
        return $query->notStarted()->where('date', date('Y-m-d'))->has('questions');
    }

    public function scopeBelongsToClassSubject(Builder $query, int $classId, int $subjectId) {
        return $query->where('class_id', $classId)->where('subject_id', $subjectId)->orderByDesc('date');
    }

    public function getHasBeenWrittenAttribute() {
        return $this->submissions->isNotEmpty();
    }

    public function getQuestionsAttribute() {
        return $this->questions()->with('options')->get();
    }

    public function hasBeenWrittenByCurrentUser() {
        return $this->students()->where('student_id', auth()->id())->exists();
    }
}
