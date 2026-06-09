<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class ShopPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {}

    public function index(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_SHOPS->value);
    }

    public function bannedShops(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_BANDED_SHOPS->value);
    }

    public function store(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_SHOP->value);
    }

    public function update(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_SHOP->value);
    }

    public function ban(User $user)
    {
        return $user->hasPermission(PermissionsEnum::BAN_SHOP->value);
    }

    public function unban(User $user)
    {
        return $user->hasPermission(PermissionsEnum::ACTIVATE_SHOP->value);
    }

    public function delete(User $user)
    {
        return $user->hasPermission(PermissionsEnum::DELETE_SHOP->value);
    }
}
