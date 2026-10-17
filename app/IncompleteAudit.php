<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncompleteAudit extends Model
{

    protected $table = 'incomplete_audits';
    protected $fillable = ['audit_id','client_id'];

}
