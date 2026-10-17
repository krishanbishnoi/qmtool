<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QmSheet extends Model
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
    public function parameter()
    {
        return $this->hasMany('App\QmSheetParameter');
    }
}
