<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class ProductPolicy
{
    /**
     * Create a new policy instance.
    */
    public function __construct()
    {}

    public function shopProducts(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_SHOP_PRODUCTS->value);
    }

    public function bannedProducts(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_BANDED_PRODUCTS->value);
    }

    public function store(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_PRODUCT->value);
    }

    public function update(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_PRODUCT->value);
    }

    public function ban(User $user)
    {
        return $user->hasPermission(PermissionsEnum::BAN_PRODUCT->value);
    }

    public function unban(User $user)
    {
        return $user->hasPermission(PermissionsEnum::ACTIVATE_PRODUCT->value);
    }

    public function delete(User $user)
    {
        return $user->hasPermission(PermissionsEnum::DELETE_PRODUCT->value);
    }
}
