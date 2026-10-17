<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rebuttal extends Model
{
    public function parameter()
    {
        return $this->belongsTo('App\QmSheetParameter');
    }
    public function sub_parameter()
    {
        return $this->belongsTo('App\QmSheetSubParameter');
    }
    public function raw_data()
    {
        return $this->hasOne('App\RawData','id','raw_data_id');
    }
    public function audit_data()
    {
        return $this->hasOne('App\Audit','id','audit_id');
    }
    public function re_rebuttal_row()
    {
        return $this->hasOne('App\Rebuttal','re_rebuttal_id','id');
    }

}
