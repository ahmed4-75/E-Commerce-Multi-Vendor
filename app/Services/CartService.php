<?php

namespace App\Services;

use App\Http\Requests\AddQuantityCartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Contracts\CartInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function __construct(
        protected CartInterface $cartRepository
    ) {}

    public function index()
    {
        return $this->cartRepository->index();
    }

    public function store()
    {
        return $this->cartRepository->store();
    }

    public function addToCart(AddQuantityCartRequest $request, int $id)
    {
        $cart = Cart::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        $product = Product::findOrFail($request->id);
        if($cart->products()->whereKey($request->id)->exists()) {
            throw ValidationException::withMessages([
                'product' => 'Product already in cart.'
            ]);
        }
        return $this->cartRepository->addToCart($request, $product, $cart);
    }

    public function show(string $lang, int $id)
    {
        $cart = Cart::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        if($cart->products()->count() === 0) {
            throw new \Exception("Cart is empty.", 400);
        }
        return $this->cartRepository->show($lang, $cart);
    }

    public function removeFromCart(Request $request, int $id)
    {
        $cart = Cart::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        $product = Product::findOrFail($request->product_id);
        return $this->cartRepository->removeFromCart($cart, $product);
    }

    public function delete(int $id)
    {
        $cart = Cart::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        $this->cartRepository->delete($cart);
    }
}
