<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public function admin_user()
    {
        return $this->belongsTo('App\User');
    }
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function partner_location()
    {
        return $this->hasMany('App\PartnerLocation');
    }
    public function partner_process()
    {
        return $this->hasMany('App\PartnersProcess','partner_id','id');
    }
}
