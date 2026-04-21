<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderedProducts extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     *  Get the product that owns the ordered product.
    */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }
    
    /**
     * Get the order that owns the ordered product.
    */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }

}
