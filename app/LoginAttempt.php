<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $table = 'login_attempts';
    protected $fillable = [
        'email',
        'ip_address'
    ];
}
