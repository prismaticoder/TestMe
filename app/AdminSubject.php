<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSubject extends Model
{
    //
    protected $table = 'admin_subject';

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'adminsubject_class', 'adminsubject_id', 'class_id');
    }
}
