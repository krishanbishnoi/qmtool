<?php

namespace App\Entrust\Traits;

/**
 * EntrustUserTrait
 *
 * In-project replacement for the abandoned zizaco/entrust package.
 * Provides hasRole(), can(), ability() methods that the application uses.
 * Kept fully backward-compatible with zizaco/entrust 5.x API.
 */
trait EntrustUserTrait
{
    /**
     * Check if the user has a given role (by name or array of names).
     *
     * @param  string|array  $name
     * @param  bool  $requireAll  When true, ALL roles must match.
     * @return bool
     */
    public function hasRole(string|array $name, bool $requireAll = false): bool
    {
        if (is_array($name)) {
            if (empty($name)) {
                return true;
            }

            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$requireAll) {
                    return true;
                }
                if (!$hasRole && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        }

        foreach ($this->cachedRoles() as $role) {
            if ($role->name === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has a permission (by name or array of names).
     *
     * Renamed from can() to hasPermission() to avoid a signature conflict with
     * Illuminate\Foundation\Auth\User::can($abilities, $arguments = []) in Laravel 11.
     *
     * @param  string|array  $permission
     * @param  bool  $requireAll
     * @return bool
     */
    public function hasPermission(string|array $permission, bool $requireAll = false): bool
    {
        if (is_array($permission)) {
            if (empty($permission)) {
                return true;
            }

            foreach ($permission as $permName) {
                $hasPerm = $this->hasPermission($permName);
                if ($hasPerm && !$requireAll) {
                    return true;
                }
                if (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        }

        foreach ($this->cachedRoles() as $role) {
            foreach ($role->perms as $perm) {
                if ($perm->name === $permission) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Alias kept for any legacy code that may call can() directly on the User model.
     * Delegates to hasPermission() to avoid conflict with Laravel's Gate::can().
     *
     * NOTE: Laravel's own Gate/Policy checks still go through the parent can() on
     * Authorizable — this alias is only reached if called directly, which the
     * app does not currently do.
     *
     * @param  mixed  $abilities
     * @param  mixed  $arguments
     * @return bool
     */
    public function can($abilities, $arguments = []): bool
    {
        // If the parent class (Gate-based) has policies/gates defined, honour them.
        // Otherwise fall through to our permission check for backwards compatibility.
        if (method_exists(get_parent_class($this), 'can')) {
            $parentResult = parent::can($abilities, $arguments);
            // parent returns false by default when no gate is defined; only trust
            // it when a gate actually exists for this ability.
            if ($parentResult || \Illuminate\Support\Facades\Gate::has(is_array($abilities) ? $abilities[0] : $abilities)) {
                return $parentResult;
            }
        }

        return $this->hasPermission($abilities);
    }

    /**
     * Check ability — shorthand for checking roles AND permissions.
     *
     * @param  string|array  $roles
     * @param  string|array  $permissions
     * @param  bool  $validateAll
     * @return bool
     */
    public function ability(string|array $roles, string|array $permissions, bool $validateAll = false): bool
    {
        $hasRoles       = $this->hasRole($roles, $validateAll);
        $hasPermissions = $this->hasPermission($permissions, $validateAll);

        if ($validateAll) {
            return $hasRoles && $hasPermissions;
        }

        return $hasRoles || $hasPermissions;
    }

    /**
     * Get the roles for this user from cache or the relationship.
     */
    protected function cachedRoles(): \Illuminate\Database\Eloquent\Collection
    {
        if (!isset($this->rolesCache)) {
            $this->rolesCache = $this->roles()->with('perms')->get();
        }

        return $this->rolesCache;
    }

    /**
     * Reset the roles cache (call after role assignment/removal).
     */
    public function flushRoleCache(): void
    {
        unset($this->rolesCache);
    }

    /**
     * Attach a role to the user.
     *
     * @param  mixed  $role
     */
    public function attachRole(mixed $role): void
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
        $this->flushRoleCache();
    }

    /**
     * Detach a role from the user.
     *
     * @param  mixed  $role
     */
    public function detachRole(mixed $role): void
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
        $this->flushRoleCache();
    }

    /**
     * Attach multiple roles to the user.
     *
     * @param  mixed  $roles
     */
    public function attachRoles(mixed $roles = []): void
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles from the user.
     *
     * @param  mixed  $roles
     */
    public function detachRoles(mixed $roles = []): void
    {
        if (empty($roles)) {
            $roles = $this->roles()->get();
        }

        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }
}
