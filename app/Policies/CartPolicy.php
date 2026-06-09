<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class CartPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {}

    public function store(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CART->value);
    }

    public function addToCart(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CART->value);
    }

    public function removeFromCart(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_CART->value);
    }
}
