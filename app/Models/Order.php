<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Get the ordered products associated with the order.
     */
    public function orderedProducts(): HasMany
    {
        return $this->hasMany(OrderedProducts::class,'order_id');
    }
}
