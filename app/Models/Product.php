<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'quantity',
        'price',
        'category_id',
        'user_id',
        'shop_id'
    ];

    /**
     * Get all of the product's translations.
    */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    /**
     * Get the category that owns the products.
    */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    /**
     * Get the shop that owns the products.
    */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class,'shop_id');
    }

    /**
     * Get the user that owns the products.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Get the product images for the blog product.
    */
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class,'product_id');
    }

    /**
     * Get the comments for the blog product.
    */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class,'product_id');
    }

    /**
     * The carts that belong to the product.
    */
    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class,'products_carts','product_id','cart_id')->withPivot('quantity','price')->as('item');
    }

    /**
     * The ordered products that belong to the product.
    */
    public function orderedProducts(): HasMany
    {
        return $this->hasMany(OrderedProducts::class,'product_id');
    }
}
