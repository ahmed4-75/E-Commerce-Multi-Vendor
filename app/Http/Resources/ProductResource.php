<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="ProductResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="quantity", type="integer", example=635),
 *     @OA\Property(property="price", type="number", example=10.60),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="user", description="Loaded only if relation user is loaded", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="category", description="Loaded only if relation category is loaded", ref="#/components/schemas/CategoryResource"),
 *     @OA\Property(property="shop", description="Loaded only if relation shop is loaded", ref="#/components/schemas/ShopResource"),
 *     @OA\Property(property="translation", ref="#/components/schemas/TranslationResource"),
 *     @OA\Property(property="productImages", type="array", description="Loaded only if relation productImages is loaded", @OA\Items(ref="#/components/schemas/ProductImageResource")),
 *     @OA\Property(property="comments", type="array", description="Loaded only if relation comments is loaded", @OA\Items(ref="#/components/schemas/CommentResource")),
 *     @OA\Property(property="carts", type="array", description="Loaded only if relation carts is loaded", @OA\Items(ref="#/components/schemas/CartResource"))
 * )
*/
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $translation = $this->translations->first();
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'category' => $this->whenLoaded('category',fn() => new CategoryResource($this->category)),
            'shop' => $this->whenLoaded('shop',fn() => new ShopResource($this->shop)),
            'translation' => $translation ? new TranslationResource($translation) : null,
            'productImages' => ProductImageResource::collection($this->whenLoaded('productImages')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'carts' => CartResource::collection($this->whenLoaded('carts'))
        ];
    }
}
