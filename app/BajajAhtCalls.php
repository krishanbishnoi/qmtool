<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BajajAhtCalls extends Model
{


    public function audits(){
        return $this->belongsTo('App\Audit');
    }


    protected $fillable = ['audit_id','qa_id','aht_time','call_id'];

    
}
