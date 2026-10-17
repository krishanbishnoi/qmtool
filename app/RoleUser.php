<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $table="role_user";
    public $timestamps=true;

    public function getUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
