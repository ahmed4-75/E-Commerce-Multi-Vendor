<?php

namespace App\Services;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Repositories\Contracts\OrderInterface;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected CartService $cartService,
    ) {}

    public function index()
    {
        return $this->orderRepository->index();
    }


    public function allOrders()
    {
        return $this->orderRepository->allOrders();
    }

    public function store(CreateOrderRequest $request)
    {
        $data = $this->cartService->show($request->lang,$request->cart_id);
        $cart = $data['cart'];
        $missing_count = $data['missing_count'];
        $missing_translation = $data['missing_translation'];
        if($cart->user_id !== Auth::id()) {
            throw new \Exception("This Cart does not belong to the authenticated user.", 403);
        }
        if($missing_translation->isNotEmpty() Or $missing_count > 0) {
            throw new \Exception("Cannot create order. There are $missing_count products in the cart that do not have translations in the requested language. All the products in the cart must be in one language.", 400);
        }
        return $this->orderRepository->store($request, $cart);
    }

    public function show(int $id)
    {
        $order = Order::whereKey('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return $this->orderRepository->show($order);
    }

    public function update(UpdateOrderRequest $request, int $id)
    {
        $order = Order::whereKey('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return $this->orderRepository->update($request, $order);
    }

    public function delete(int $id)
    {
        $order = Order::whereKey('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return $this->orderRepository->delete($order);
    }
}
