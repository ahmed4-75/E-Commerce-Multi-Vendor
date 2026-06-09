<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

interface OrderInterface
{
    public function index();
    public function ordersProduct(Product $product);
    public function allOrders();
    public function store(CreateOrderRequest $request, Cart $cart);
    public function show(Order $order);
    public function update(UpdateOrderRequest $request, Order $order);
    public function shipping(Order $order);
    public function delivered(Order $order);
    public function deleteReady(Order $order);
    public function delete(Order $order);
}
