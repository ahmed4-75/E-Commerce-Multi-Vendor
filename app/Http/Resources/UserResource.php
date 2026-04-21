<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="name", type="string", example="user name"),
 *     @OA\Property(property="email", type="string", example="name@test.com"),
 *     @OA\Property(property="phone", type="string", example="+2 01065484974"),
 *     @OA\Property(property="lang", type="string", example="ar"),
 *     @OA\Property(property="favicon", type="string", example="user favicon"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="products", type="array", description="Loaded only if relation products is loaded", @OA\Items(ref="#/components/schemas/ProductResource")),
 *     @OA\Property(property="carts", type="array", description="Loaded only if relation carts is loaded", @OA\Items(ref="#/components/schemas/CartResource")),
 *     @OA\Property(property="orders", type="array", description="Loaded only if relation orders is loaded", @OA\Items(ref="#/components/schemas/OrderResource")),
 *     @OA\Property(property="comments", type="array", description="Loaded only if relation comments is loaded", @OA\Items(ref="#/components/schemas/CommentResource")),
 *     @OA\Property(property="shops", type="array", description="Loaded only if relation shops is loaded", @OA\Items(ref="#/components/schemas/ShopResource"))
 * )
*/
class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'lang' => $this->lang,
            'favicon' => $this->favicon,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'active' => $this->is_active,

            'products' => ProductResource::collection($this->whenLoaded('products')),
            'carts' => CartResource::collection($this->whenLoaded('carts')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'shops' => ShopResource::collection($this->whenLoaded('shops'))
        ];
    }
}
