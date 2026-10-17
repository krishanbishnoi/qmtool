<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QmSheetSubParameter extends Model
{
    public function audit_alert_box()
    {
        return $this->belongsTo('App\AuditAlertBox');
    }
}
