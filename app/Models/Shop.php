<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'phone',
        'email',
        'user_id',
        'pincode',
        'website',
        'bank_name',
        'bank_code',
        'bank_country',
        'bank_address',
        'account_name',
        'account_number'
    ];

    /**
     * Get the user that owns the shop.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Get the products for the shop.
    */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'shop_id');
    }
}
