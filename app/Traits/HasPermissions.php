<?php
namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
trait HasPermissions
{
    /**
     * Check if the user has a given permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->staff
        && $this->staff->roles->flatMap->permissions->contains('permission', $permission);
    }

    /**
     * Check if the user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->staff
        && $this->staff->roles->flatMap->permissions
            ->whereIn('permission', $permissions)
            ->isNotEmpty();
    }

    public function authorizePermission(string $permission)
    {
        if (! $this->hasPermission($permission)) {
            throw new AuthorizationException('You do not have permission to perform this action.');
        }
    }

    /**
     * Check if the user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->staff
        && collect($permissions)->every(fn($perm) =>
            $this->staff->roles->flatMap->permissions->contains('permission', $perm)
        );
    }
}
