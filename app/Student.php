<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    use SoftDeletes; // use the trait

    protected $dates = ['deleted_at']; // mark this column as a date

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'class_id', 'code'
    ];
    protected $appends = ['fullName'];

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }

    public function getFullNameAttribute() {
        return ucfirst(strtolower($this->lastname)) . ' ' . ucfirst(strtolower($this->firstname));
    }

    public function getScore($exam_id) {
        $score = $this->scores()->where('exam_id', $exam_id)->first();

        return $score ? ['actual_score' => $score->actual_score, 'computed_score' => $score->computed_score] : null;
    }

    public function getAllStartedExams() {
        $exams = Exam::where('class_id', $this->class_id)->where('hasStarted',1)->whereDoesntHave('scores',function($q) {
            $q->where('student_id',$this->id);
        })->get();

        return $exams;
    }
}
