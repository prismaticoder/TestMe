<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Exam extends Model
{
    protected $appends = ['hasBeenWritten', 'questions', 'code', 'has_started'];
    protected $hidden = [
        'code',
    ];

    protected $guarded = ['id'];

    protected $dates = [
        'started_at',
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
            $exam->code = self::generateUniqueExaminationCode($exam->class_id, $exam->subject_id);
        });
    }

    /**
     * Start an exam.
     *
     * @return void
     */
    public function start()
    {
        $this->update(['started_at' => now()]);
    }

    /**
     * End an exam.
     *
     * @return void
     */
    public function end()
    {
        $this->update(['started_at' => null]);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'submissions')
            ->withPivot('actual_score', 'computed_score')
            ->withoutGlobalScopes();
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function getHasStartedAttribute(): bool
    {
        return (bool) $this->started_at;
    }

    public function scopeStarted($query)
    {
        return $query->whereNotNull('started_at');
    }

    public function scopeStartedByCurrentUser($query)
    {
        return $query->when(auth()->user()->isTeacher(), function ($query) {
            return $query->whereIn('subject_id', Arr::pluck(auth()->user()->subjects, 'subject_id'));
        })->started();
    }

    public function scopeNotStarted($query)
    {
        return $query->whereNull('started_at')->latest('updated_at');
    }

    public function scopeCanBeStarted($query)
    {
        return $query->notStarted()->where('date', date('Y-m-d'))->has('questions');
    }

    public function scopeBelongsToClassSubject(Builder $query, int $classId, int $subjectId)
    {
        return $query->where('class_id', $classId)->where('subject_id', $subjectId)->orderByDesc('date');
    }

    public function getAveragePointAttribute()
    {
        return $this->aggregate_score / $this->questions->count();
    }

    public function getHasBeenWrittenAttribute()
    {
        return $this->submissions->isNotEmpty();
    }

    public function getQuestionsAttribute()
    {
        return $this->questions()->with('options')->get();
    }

    public function hasBeenWrittenByCurrentUser()
    {
        return $this->students()->where('student_id', auth()->id())->exists();
    }

    public static function generateUniqueExaminationCode(int $classId, int $subjectId): string
    {
        do {
            $uniqueCode = $classId.'-'.$subjectId.'-'.mt_rand(10, 99);
        } while (self::where(['code' => $uniqueCode])->exists());

        return $uniqueCode;
    }
}
