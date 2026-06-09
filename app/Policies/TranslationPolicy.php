<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class TranslationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {}

    public function addToCategory(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CATEGORY->value);
    }

    public function removeFromCategory(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CATEGORY->value);
    }

    public function addToProduct(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CATEGORY->value);
    }

    public function removeFromProduct(User $user): bool
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CATEGORY->value);
    }
}
