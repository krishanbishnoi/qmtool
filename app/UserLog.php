<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'action',
        'details',
        'log_in_time',
        'log_out_time'
    ];
}
