<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public function admins() {
        return $this->hasOne(Admin::class, 'AdminRoleId');
        }
}
