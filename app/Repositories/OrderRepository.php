<?php

namespace App\Repositories;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderInterface
{
    public function index()
    {
        return Order::query()->where('user_id', Auth::id())->paginate(10);
    }

    public function ordersProduct(Product $product)
    {
        return $product->orders()->paginate(10);
    }

    public function allOrders()
    {
        return Order::query()->paginate(10);
    }

    public function store(CreateOrderRequest $request, Cart $cart)
    {
        DB::transaction(function () use ($request, $cart) {
            $order = Order::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'lang' => $request->lang,
                'amount' => $cart->cart_total,
                'currency' => $request->currency
            ]);
            $orderProducts = [];
            foreach ($cart->products as $product) {
                $orderProducts[$product->id] = [
                    'quantity' => $product->item->quantity,
                    'price' => $product->item->price,
                ];
            }
            $order->products()->attach($orderProducts);

            Cart::destroy($cart->id);
        });
    }

    public function show(Order $order)
    {
        $order->load([
            'products' => function ($query) use ($order) {
                $query->withTrashed()
                ->with(['translations' => function ($query) use ($order) {$query->where('lang', $order->lang);}]);
            }
        ]);
        return $order;
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update(['note' => $request->note]);
    }

    public function shipping(Order $order)
    {
        $order->update(['status' => 'shipping']);
    }

    public function delivered(Order $order)
    {
        $order->update(['status' => 'done']);
    }

    public function deleteReady(Order $order)
    {
        $order->update(['status' => 'Delete']);
    }

    public function delete(Order $order)
    {
        Order::destroy($order->id);
    }
}
