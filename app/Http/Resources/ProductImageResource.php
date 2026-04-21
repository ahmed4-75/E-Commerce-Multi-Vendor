<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="ProductImageResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="image_path", type="string", example="image path"),
 *     @OA\Property(property="page_name", type="string", example="page name"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="product", description="Loaded only if relation product is loaded", ref="#/components/schemas/ProductResource")
 * )
*/
class ProductImageResource extends JsonResource
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
            'image_path' => $this->image_path,
            'page_name' => $this->page_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product' => $this->whenLoaded('product',fn() => new ProductResource($this->product))
        ];
    }
}
