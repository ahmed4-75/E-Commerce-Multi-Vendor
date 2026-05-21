<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CartResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="products_count",type="integer",example=3),
 *     @OA\Property(property="products",type="array",description="Loaded only if products relation is loaded",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Product name"),
 *             @OA\Property(property="description", type="string", example="Product description"),
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
class CartResource extends JsonResource
{
    protected string| null $lang;
    public function __construct(mixed $resource, ?string $lang = null)
    {
        parent::__construct($resource);
        $this->lang = $lang;
    }
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $this->lang;
        return [
            'id' => $this->id,
            'products_count' => $this->products->count(),
            'products' => $this->whenLoaded('products',fn() => $this->items($lang)),
            'cart_total' => $this->cart_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user',fn() => new UserResource($this->user))
        ];
    }
}
