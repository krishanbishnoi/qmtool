<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TmpAudit extends Model
{
    protected $table = 'tmp_audits';

    public function tmp_audit_parameter_result()
    {
        return $this->hasMany('App\TmpAuditParameterResult','audit_id');
    }
    public function tmp_audit_results()
    {
        return $this->hasMany('App\TmpAuditResult','audit_id');
    }
    public function raw_data()
    {
        return $this->belongsTo('App\RawData');
    }
    public function client()
     {
         return $this->belongsTo('App\Client');
     }
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
    public function qa_qtl_detail()
    {
        return $this->belongsTo('App\User','audited_by_id','id');
    }
    public function audit_rebuttal()
    {
        return $this->hasMany('App\Rebuttal','audit_id','id');
    }
    public function audit_rebuttal_accepted()
    {
        return $this->hasMany('App\Rebuttal','audit_id')->where('status',1);
    }
    public function audit_rebuttal_rejected()
    {
        return $this->hasMany('App\Rebuttal','audit_id')->where('status',2);
    }   
    public function auditor()
    {
        return $this->belongsTo('App\User','audited_by_id');
    }
    public function qc_defect_sub_parameter()
    {
        return $this->hasMany('App\QcDefectSubParameter','audit_id');
    }

}
