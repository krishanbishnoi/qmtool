<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrbanCase extends Model
{

    protected $fillable = ['case_name','case_id','process_id','qa_id','client_id'];

    public function audit_cases()
    {
        return $this->hasMany('App\AuditCase','case_id');
    }
}
