<?php

namespace App\Entrust;

use App\Entrust\Traits\EntrustRoleTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * EntrustRole base model.
 *
 * In-project replacement for Zizaco\Entrust\EntrustRole.
 */
abstract class EntrustRole extends Model
{
    use EntrustRoleTrait;

    /**
     * Many-to-many relationship with Permission.
     */
    public function perms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            \App\Permission::class,
            config('entrust.permission_role_table', 'permission_role')
        );
    }

    /**
     * Many-to-many relationship with User.
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.providers.users.model', \App\User::class),
            config('entrust.role_user_table', 'role_user')
        );
    }
}
