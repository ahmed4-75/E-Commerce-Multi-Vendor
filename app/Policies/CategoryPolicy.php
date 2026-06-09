<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {}

    public function store(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CATEGORY->value);
    }

    public function show(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_CATEGORY->value);
    }

    public function update(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CATEGORY->value);
    }

    public function delete(User $user)
    {
        return $user->hasPermission(PermissionsEnum::DELETE_CATEGORY->value);
    }
}
