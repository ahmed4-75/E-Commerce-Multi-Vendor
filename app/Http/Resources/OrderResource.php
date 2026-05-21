<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderResource",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *
 *     @OA\Property(property="first_name", type="string", example="first name"),
 *     @OA\Property(property="last_name", type="string", example="last name"),
 *     @OA\Property(property="email", type="string", example="name@test.com"),
 *     @OA\Property(property="address", type="string", example="user customer address"),
 *     @OA\Property(property="phone",type="string",nullable=true,example="+2 01065484974"),
 *     @OA\Property(property="lang", type="string", example="ar"),
 *     @OA\Property(property="amount",type="number",format="float",example=964.23),
 *     @OA\Property(property="currency", type="string", example="EGP"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="payment_gateway",type="string",nullable=true,example="paypal"),
 *     @OA\Property(property="gateway_order_id",type="string",nullable=true,example="PAYPAL_ORDER_123"),
 *     @OA\Property(property="transaction_id",type="string",nullable=true,example="asd k66651"),
 *     @OA\Property(property="note",type="string",nullable=true,example="order note"),
 *     @OA\Property(property="products_count",type="integer",example=3),
 *     @OA\Property(property="products",type="array",description="Loaded only if relation products is loaded",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Product name"),
 *             @OA\Property(property="description", type="string", example="Description"),
 *             @OA\Property(property="quantity", type="integer", example=2),
 *             @OA\Property(property="price", type="number", format="float", example=100),
 *             @OA\Property(property="total_price", type="number", format="float", example=200)
 *         )
 *     ),
 *     @OA\Property(property="cart_total",type="number",format="float",example=6934.60),
 *     @OA\Property(property="created_at",type="string",format="date-time"),
 *     @OA\Property(property="updated_at",type="string",format="date-time"),
 *     @OA\Property(property="user",description="Loaded only if relation user is loaded",ref="#/components/schemas/UserResource")
 * )
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'lang' => $this->lang,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment_gateway' => $this->payment_gateway,
            'gateway_order_id' => $this->gateway_order_id,
            'transaction_id' => $this->transaction_id,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products_count' => $this->products->count(),
            'products' => $this->whenLoaded('products',fn() => $this->items()),
            'cart_total' => $this->cart_total,

            'user' => $this->whenLoaded('user',fn() => new UserResource($this->user))
        ];
    }
}
