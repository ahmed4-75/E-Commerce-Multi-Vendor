<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Order;

interface OrderInterface
{
    public function index();
    public function allOrders();
    public function store(CreateOrderRequest $request, Cart $cart);
    public function show(Order $order);
    public function update(UpdateOrderRequest $request, Order $order);
    public function delete(Order $order);
}
