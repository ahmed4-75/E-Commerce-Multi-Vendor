<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'lang',
        'otp',
        'favicon'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the products for the blog user.
    */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'user_id');
    }

    /**
     * Get the cart associated with the user.
    */
    public function carts(): HasOne
    {
        return $this->hasOne(Cart::class,'user_id');
    }

    /**
     * Get the orders for the blog user.
    */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'user_id');
    }

    /**
     * Get the comments for the blog user.
    */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class,'user_id');
    }

    /**
     * Get the shops for the blog user.
    */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class,'user_id');
    }

    public function getIsActiveAttribute(): bool
    {
        return !$this->trashed();
    }
}
