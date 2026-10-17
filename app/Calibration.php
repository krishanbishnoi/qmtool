<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calibration extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function process()
    {
        return $this->belongsTo('App\Process');
    }
    public function qm_sheet()
    {
        return $this->belongsTo('App\QmSheet');
    }
    public function calibrator()
    {
        return $this->hasMany('App\Calibrator');
    }
    
}
