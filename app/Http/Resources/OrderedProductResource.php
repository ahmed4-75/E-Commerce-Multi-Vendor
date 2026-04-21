<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="OrderedProductResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="price", type="number", example=100),
 *     @OA\Property(property="total", type="number", example=200),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="product", description="Loaded only if relation product is loaded", ref="#/components/schemas/ProductResource")
 *     @OA\Property(property="order", description="Loaded only if relation order is loaded", ref="#/components/schemas/OrderResource")
 * )
*/
class OrderedProductResource extends JsonResource
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
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product' => $this->whenLoaded('product',fn() => new ProductResource($this->product)),
            'order'=> $this->whenLoaded('order',fn() => new OrderResource($this->order))
        ];
    }
}
