<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'user_id',
        'content',
        'product_id'
    ];

    /**
     * Get the product that owns the comments.
    */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    /**
     * Get the user that owns the comments.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
