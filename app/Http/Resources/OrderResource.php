<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="OrderResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="first_name", type="string",example="first name"),
 *     @OA\Property(property="last_name", type="string",example="last name"),
 *     @OA\Property(property="email", type="string",example="name@test.com"),
 *     @OA\Property(property="address", type="string",example="user customer address"),
 *     @OA\Property(property="phone", type="string", nullable=true,example="+2 01065484974"),
 *     @OA\Property(property="amount", type="number",example=964.23),
 *     @OA\Property(property="currency", type="string",example="EGP"),
 *     @OA\Property(property="status", type="string",example="pending"),
 *     @OA\Property(property="payment_gateway", type="string", nullable=true,example="paypal"),
 *     @OA\Property(property="gateway_order_id", type="integer", nullable=true,example=1),
 *     @OA\Property(property="transaction_id", type="string", nullable=true,example="asd k66651"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="note", type="string", nullable=true,example="order note"),
 *     @OA\Property(property="user", description="Loaded only if relation user is loaded", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="orderedProducts", type="array", description="Loaded only if relation orderedProducts is loaded", @OA\Items(ref="#/components/schemas/OrderedProductResource"))
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
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment_gateway' => $this->payment_gateway,
            'gateway_order_id' => $this->gateway_order_id,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'note' => $this->note,

            'user' => $this->whenLoaded('user',fn() => new UserResource($this->user)),
            'orderedProducts' => OrderedProductResource::collection($this->whenLoaded('orderedProducts'))
        ];
    }
}
