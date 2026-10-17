<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedMonthTarget extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client','client_id');
    }
    public function process()
    {
        return $this->belongsTo('App\Process','process_id');
    }
    public function partner()
    {
        return $this->belongsTo('App\Partner','partner_id');
    }
    public function uploader()
    {
        return $this->belongsTo('App\User','uploaded_by');
    }
    public function audit_cycle()
    {
        return $this->belongsTo('App\Auditcycle','month_of_target');
    }
}
