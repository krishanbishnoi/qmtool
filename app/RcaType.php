<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RcaType extends Model
{
    protected $guarded = ['_token'];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function process()
    {
        return $this->belongsTo('App\Process');
    }
}
