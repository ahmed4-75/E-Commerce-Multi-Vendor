<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


/**
 * @OA\Schema(
 *     schema="CartResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="items",type="array",@OA\Items(
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Product name"),
 *         @OA\Property(property="description", type="string", example="Product description"),
 *         @OA\Property(property="quantity", type="integer", example=2),
 *         @OA\Property(property="price", type="number", example=100),
 *         @OA\Property(property="product_total", type="number", example=200)
 *         )
 *     ),
 *     @OA\Property(property="cart_total", type="number", example=6934.60),
 *     @OA\Property(property="user", description="Loaded only if relation user is loaded", ref="#/components/schemas/UserResource")
 * )
*/
class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->lang ?? Auth::user()?->lang ?? app()->getLocale();
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'items' => $this->items($lang),
            'cart_total' => $this->cart_total,

            'user' => $this->whenLoaded('user',fn() => new UserResource($this->user))
        ];
    }
}
