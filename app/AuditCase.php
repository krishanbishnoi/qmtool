<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditCase extends Model
{

    protected $fillable = ['case_id','audit_id','raw_data_id','auditor_id','case_name'];

    public function casess()
    {
        return $this->belongsTo('App\UrbanCase');
    }
    public function cases_audits()
    {
        return $this->belongsTo(Audit::class, 'audit_id');
    }
}
