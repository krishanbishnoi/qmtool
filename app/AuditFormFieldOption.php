<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditFormFieldOption extends Model
{
     protected $table = 'audit_form_field_options';
      protected $fillable = ['field_id', 'option_value', 'status'];
     public function field()
     {

          
          return $this->belongsTo(
               AuditFormFields::class,
               'field_id'
          );

         
     }
}
