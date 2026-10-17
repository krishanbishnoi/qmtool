<?php

namespace App\Entrust\Traits;

/**
 * EntrustRoleTrait
 *
 * In-project replacement for the abandoned zizaco/entrust package.
 * Provides permission-checking methods for the Role model.
 */
trait EntrustRoleTrait
{
    /**
     * Check if the role has a given permission.
     *
     * Renamed from can() to hasPermission() for consistency with EntrustUserTrait.
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

        foreach ($this->perms as $perm) {
            if ($perm->name === $permission) {
                return true;
            }
        }

        return false;
    }

    /**
     * Alias for backwards compatibility.
     */
    public function can(string|array $permission, bool $requireAll = false): bool
    {
        return $this->hasPermission($permission, $requireAll);
    }

    /**
     * Attach a permission to the role.
     *
     * @param  mixed  $permission
     */
    public function attachPermission(mixed $permission): void
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->attach($permission);
    }

    /**
     * Detach a permission from the role.
     *
     * @param  mixed  $permission
     */
    public function detachPermission(mixed $permission): void
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->detach($permission);
    }

    /**
     * Attach multiple permissions to the role.
     *
     * @param  mixed  $permissions
     */
    public function attachPermissions(mixed $permissions = []): void
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Detach multiple permissions from the role.
     *
     * @param  mixed  $permissions
     */
    public function detachPermissions(mixed $permissions = []): void
    {
        if (empty($permissions)) {
            $permissions = $this->perms()->get();
        }

        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }
}
