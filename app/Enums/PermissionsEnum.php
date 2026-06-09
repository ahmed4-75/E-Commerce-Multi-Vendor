<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    //Users
    case VIEW_USERS = 'view-users';
    case CHANGE_USER_ROLES = 'change-user-roles';
    case BAN_USER = 'ban-user';
    case ACTIVATE_USER = 'activate-user';
    case DESTROY_USER = 'destroy-user';
    //Roles
    case VIEW_ROLES = 'view-roles';
    case CREATE_ROLE = 'create-role';
    case UPDATE_ROLE = 'update-role';
    case DELETE_ROLE = 'delete-role';
    //Shops
    case VIEW_SHOPS = 'view-shops';
    case VIEW_BANDED_SHOPS = 'view-banded_shops';
    case CREATE_SHOP = 'create-shop';
    case BAN_SHOP = 'ban-shop';
    case ACTIVATE_SHOP = 'activate-shop';
    case DELETE_SHOP = 'delete-shop';
    //Products
    case VIEW_SHOP_PRODUCTS = 'view-shop-products';
    case VIEW_BANDED_PRODUCTS = 'view-banded-products';
    case CREATE_PRODUCT = 'create-product';
    case BAN_PRODUCT = 'ban-product';
    case ACTIVATE_PRODUCT = 'activate-product';
    case DELETE_PRODUCT = 'delete-product';
    //Categories
    case CREATE_CATEGORY = 'create-category';
    case VIEW_CATEGORY = 'view-category';
    case DELETE_CATEGORY = 'delete-category';
    //Carts
    case CREATE_CART = 'create-cart';
    //Orders
    case VIEW_ORDERS = 'view-orders';
    case VIEW_ORDERS_PRODUCT = 'view-orders-product';
    case CREATE_ORDER = 'create-order';
    case SHIPPING_ORDER = 'shipping-order';
    case DELIVERED_ORDER = 'delivered-order';
    case DELETE_ORDER = 'delete-order';


    public static function values(): array
    {
        return array_column(self::cases(),'value');
    }
}
