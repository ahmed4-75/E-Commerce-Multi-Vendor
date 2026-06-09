<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
    */
    public function __construct()
    {}

    public function allOrders(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_ORDERS->value);
    }

    public function ordersProduct(User $user)
    {
        return $user->hasPermission(PermissionsEnum::VIEW_ORDERS_PRODUCT->value);
    }

    public function store(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_ORDER->value);
    }

    public function update(User $user)
    {
        return $user->hasPermission(PermissionsEnum::CREATE_ORDER->value);
    }

    public function shipping(User $user)
    {
        return $user->hasPermission(PermissionsEnum::SHIPPING_ORDER->value);
    }

    public function delivered(User $user)
    {
        return $user->hasPermission(PermissionsEnum::DELIVERED_ORDER->value);
    }

    public function delete(User $user)
    {
        return $user->hasPermission(PermissionsEnum::DELETE_ORDER->value);
    }
}
