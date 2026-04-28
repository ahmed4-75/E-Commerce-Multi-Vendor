<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="image_path", type="string", example="devices"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="translation", ref="#/components/schemas/TranslationResource"),
 *     @OA\Property(property="products", type="array", description="Loaded only if relation products is loaded", @OA\Items(ref="#/components/schemas/ProductResource"))
 * )
*/
class CategoryResource extends JsonResource
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
            'image_path' => $this->image_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'translation' => $translation ? new TranslationResource($translation) : null,
            'products' => ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
