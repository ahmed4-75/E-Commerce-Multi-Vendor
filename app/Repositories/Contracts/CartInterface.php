<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\AddQuantityCartRequest;
use App\Models\Cart;
use App\Models\Product;

interface CartInterface
{
    public function index();
    public function store();
    public function addToCart(AddQuantityCartRequest $request, Product $product, Cart $cart);
    public function show(string $lang, Cart $cart);
    public function removeFromCart(Cart $cart, Product $product);
    public function delete(Cart $cart);
}
