<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calibrator extends Model
{
    public function calibration()
    {
    	return $this->belongsTo('App\Calibration');
    }

    public function calibration_parameter_result()
    {
        return $this->hasMany('App\CalibrationParameterResult','audit_id');
    }
    public function calibration_results()
    {
        return $this->hasMany('App\CalibrationResult','audit_id');
    }
}
