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
        'firstname', 'lastname', 'class_id', 'examination_number'
    ];
    protected $appends = ['fullName'];

    public function exams() {
        return $this->belongsToMany(Exam::class, 'submissions')->withPivot('actual_score','computed_score');
    }

    public function submissions() {
        return $this->hasMany(Submission::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }

    public function getFullNameAttribute() {
        return ucwords(
            strtolower("{$this->lastname} {$this->firstname}")
        );
    }

    public function getSeedAttribute() {
        return substr($this->code, 0, 4);
    }

    public function getAvailableExams() {

        return Exam::started()
                ->where('class_id', $this->class_id)
                ->whereDoesntHave('submissions',function($q) {
                    $q->where('student_id',$this->id);
                })
                ->get();

    }

    public static function generateExaminationNumber(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRTUVWXYZ';
        $code = mt_rand(2111, 9999) . $characters[rand(0, strlen($characters) - 1)] . $characters[rand(0, strlen($characters) - 1)];

        $check = self::query()->where('examination_number', $code)->exists();

        return $check ? self::generateExaminationNumber() : $code;
    }
}
