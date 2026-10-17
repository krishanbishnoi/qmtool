<?php

namespace App\Entrust;

use Illuminate\Database\Eloquent\Model;

/**
 * EntrustPermission base model.
 *
 * In-project replacement for Zizaco\Entrust\EntrustPermission.
 */
abstract class EntrustPermission extends Model
{
    /**
     * Many-to-many relationship with Role.
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            \App\Role::class,
            config('entrust.permission_role_table', 'permission_role')
        );
    }
}
