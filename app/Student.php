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
        'firstname', 'lastname', 'class_id', 'examination_number', 'deactivated_at',
    ];

    protected $appends = ['fullName'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($student) {
            $student->examination_number = self::generateExaminationNumber();
        });
    }

    public function deactivate()
    {
        $this->update(['deactivated_at' => now()]);
    }

    public function reactivate()
    {
        $this->update(['deactivated_at' => null]);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'submissions')->withPivot('actual_score', 'computed_score');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class)->withoutGlobalScopes();
    }

    public function getFullNameAttribute()
    {
        return strtoupper("{$this->lastname} {$this->firstname}");
    }

    public function getSeedAttribute()
    {
        return substr($this->examination_number, 0, 4);
    }

    public function getAvailableExams()
    {
        return Exam::started()
            ->where('class_id', $this->class_id)
            ->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $this->id))
            ->get();
    }

    private static function generateExaminationNumber(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRTUVWXYZ';

        $firstFourCharacters = mt_rand(2111, 9999);
        $fifthCharacter = $characters[rand(0, strlen($characters) - 1)];
        $sixthCharacter = $characters[rand(0, strlen($characters) - 1)];

        $uniqueCode = "{$firstFourCharacters}{$fifthCharacter}{$sixthCharacter}";

        $check = self::withTrashed()->where('examination_number', $uniqueCode)->exists();

        return $check ? self::generateExaminationNumber() : $uniqueCode;
    }
}
