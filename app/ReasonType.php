<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReasonType extends Model
{
    protected $guarded = ['_token','reasons'];

    public function reasons()
    {
        return $this->hasMany('App\Reason');
    }
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function process()
    {
        return $this->belongsTo('App\Process');
    }
}
