<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
        'phone',
        'user_id',
        'lang',
        'amount',
        'currency',
        'status',
        'note',
        'payment_gateway',
        'gateway_order_id',
        'transaction_id'
    ];

    /**
     * Get the user that owns the order.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * The products that belong to the order.
    */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'ordered_products','order_id','product_id')
        ->withPivot(['quantity','price'])->as('item');
    }

        /**
     * Get the items in the cart.
     */
    public function items()
    {
        $this->loadMissing('products.translations');
        if ($this->products->isEmpty()) { return []; }
        return $this->products->map(function ($product) {
            $translation = $product->translations->firstWhere('lang', $this->lang);
            return [
                'id' => $product->id,
                'name' => $translation?->name,
                'description' => $translation?->description,
                'quantity' => $product->item?->quantity,
                'price' => $product->item?->price,
                'total_price' => round(($product->item?->quantity ?? 0) * ($product->item?->price  ?? 0),2)
            ];
        });
    }

    /**
     * Get the total price of the cart.
    */
    public function getCartTotalAttribute()
    {
        return round($this->products->sum(function ($product) {
            return $product->item->quantity * $product->item->price;
        }),2);
    }
}
