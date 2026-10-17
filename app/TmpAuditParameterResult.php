<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TmpAuditParameterResult extends Model
{
    protected $table = 'tmp_audit_parameter_results';
    public function qa_qtl_detail()
    {
        return $this->belongsTo('App\User','audited_by_id','id');
    }
    public function parameter_detail()
    {
        return $this->belongsTo('App\QmSheetParameter','parameter_id','id');
    }
}
