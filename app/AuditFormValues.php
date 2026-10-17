<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditFormValues extends Model
{
    protected $table = 'audit_field_values';
    protected $fillable = ['audit_id','field_id','field_value'];
    public function field()
    {
        return $this->belongsTo(
            AuditFormFields::class,
            'field_id'
        );
    }
}
