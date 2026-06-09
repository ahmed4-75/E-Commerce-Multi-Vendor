<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {}

    public function index(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::VIEW_ROLES->value);
    }

    public function store(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CREATE_ROLE->value);
    }

    public function update(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CREATE_ROLE->value);
    }

    public function destroy(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::DELETE_ROLE->value);
    }
}
