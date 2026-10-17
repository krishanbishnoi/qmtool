<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditParameterResult extends Model
{
    public function qa_qtl_detail()
    {
        return $this->belongsTo('App\User','audited_by_id','id');
    }
    public function parameter_detail()
    {
        return $this->belongsTo('App\QmSheetParameter','parameter_id','id');
    }

}
