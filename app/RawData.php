<?php

namespace App;
use App\Scopes\RawDumpsActiveScope;
use Illuminate\Database\Eloquent\Model;

class RawData extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RawDumpsActiveScope);
    }
    public function partner_detail()
    {
        return $this->belongsTo('App\Partner','partner_id','id');
    }

    public function getQA() {
         return $this->hasOne('App\User','id','qa_id');
    }

    public function audit()
    {
        return $this->hasOne('App\Audit','raw_data_id');
    }
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
    public function partner_location()
    {
        return $this->belongsTo('App\PartnerLocation','partner_location_id','id');
    }
    public function location_data()
    {
        return $this->belongsTo('App\Region','partner_location_id');
    }

    /**
     * Get the user's history.
     */
    public function audit_rebuttal()
    {
        return $this->hasMany('App\Rebuttal','raw_data_id');
    }
    public function process()
    {
        return $this->hasOne('App\Process','id','process_id');
    }
    


}
