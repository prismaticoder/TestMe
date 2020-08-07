<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
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

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function getFullName() {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getScore($exam_id) {
        $score = $this->scores()->where('exam_id', $exam_id)->first();

        return $score ? ['actual_score' => $score->actual_score, 'computed_score' => $score->computed_score] : null;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
      */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}
