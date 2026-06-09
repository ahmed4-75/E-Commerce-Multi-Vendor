<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
    */
    public function __construct()
    {}

    public function index(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::VIEW_USERS->value);
    }

    public function changeRole(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CHANGE_USER_ROLES->value);
    }

    public function ban(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::BAN_USER->value);
    }

    public function activate(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::ACTIVATE_USER->value);
    }

    public function destroy(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::DESTROY_USER->value);
    }
}
