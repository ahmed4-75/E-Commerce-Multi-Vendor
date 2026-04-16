<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'user_id'
    ];

    /**
     * Get the user that owns the cart.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * The products that belong to the cart.
    */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'products_carts','cart_id','product_id')->withPivot('quantity','price')->as('item');
    }

    public function getCartTotalAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->item->quantity * $product->item->price;
        });
    }
    
}
