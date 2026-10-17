<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReLabel extends Model
{
    protected $guarded=['_token','re_label_id'];
}
