<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditAlertBox extends Model
{
    protected $guarded=['_token','files'];
}
