<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoggedUser extends Model
{
    protected $fillable = [
        'user_id','logged_ip'
    ];
}
