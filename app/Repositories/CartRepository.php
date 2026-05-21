<?php

namespace App\Repositories;

use App\Http\Requests\AddQuantityCartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Contracts\CartInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartInterface
{
    public function index()
    {
        $carts = Cart::query()->where('user_id', Auth::id())->get();
        return $carts;
    }

    public function store()
    {
        Cart::create([
            'user_id' => Auth::id(),
        ]);
    }

    public function addToCart(AddQuantityCartRequest $request, Product $product, Cart $cart)
    {
        $price = $product->price - $product->discount;

        DB::transaction(function () use ($product, $request, $cart, $price) {
            $cart->products()->syncWithoutDetaching([$product->id => [
                'quantity' => $request->quantity,
                'price' => $price
            ]]);
            // Update product quantity
            $product->decrement('quantity',$request->quantity,[]);
        });
    }

    public function show(string $lang, Cart $cart)
    {
        $cart->load('products.translations');

        $allProducts = $cart->products;

        $filteredProducts = $allProducts->filter(function ($product) use ($lang) {
            return $product->translations->contains('lang', $lang);
        });

        $missingProducts = $allProducts->diff($filteredProducts);

        $missing_count = $missingProducts->count();
        $missing = $missingProducts->map(function ($product) {
            return [
                'product_id' => $product->id,
                'reason' => 'product exists but not in requested lang'
            ];
        });

        $cart->setRelation('products', $filteredProducts->values());
        return [
            'cart' => $cart,
            'missing_translation' => $missing->values(),
            'missing_count' => $missing_count
        ];
    }

    public function removeFromCart(Cart $cart, Product $product)
    {
        $productCart = $cart->products()->whereKey($product->id)->firstOrFail()->pivot;

        DB::transaction(function () use ($cart, $productCart, $product) {
            // Update product quantity
            $product->increment('quantity',$productCart->quantity,[]);
            $cart->products()->detach($product->id);
        });
    }

    public function delete(Cart $cart)
    {
        $products = $cart->products;

        DB::transaction(function () use ($products, $cart) {
            foreach ($products as $product) {
                $product->increment('quantity',$product->pivot->quantity,[]);
            }
            Cart::destroy($cart->id);
        });
    }
}
