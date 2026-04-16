<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'image_path',
        'product_id',
        'page_name'
    ];

    /**
     * Get the product that owns the Product images.
    */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
