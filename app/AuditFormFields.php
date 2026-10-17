<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditFormFields extends Model
{
      protected $table = 'audit_form_fields';

      public function options()
      {
            return $this->hasMany(
                  AuditFormFieldOption::class,
                  'field_id'
            );
      }

      public function process()
      {
            return $this->belongsTo(
                  Process::class,
                  'process_id'
            );
      }
}
