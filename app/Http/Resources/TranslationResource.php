<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="TranslationResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="name", type="string", example="product name"),
 *     @OA\Property(property="description", type="string", example="product description"),
 *     @OA\Property(property="lang", type="string", example="ar"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="type", type="string", example="category or product"),
 *     @OA\Property(
 *         property="translationable",
 *         oneOf={@OA\Schema(ref="#/components/schemas/CategoryResource"),@OA\Schema(ref="#/components/schemas/ProductResource")},
 *         nullable=true,description="Polymorphic relation (Category or Product), returned only if loaded"
 *     ),
 * )
*/
class TranslationResource extends JsonResource
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
            'description' => $this->description,
            'lang' => $this->lang,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,

            'translationable' => $this->whenLoaded('translationable', 
                function () {
                    return match (true) {
                        $this->translationable instanceof Category => new CategoryResource($this->translationable),
                        $this->translationable instanceof Product => new ProductResource($this->translationable),
                        default => null,
                    };
                }
            ),
        ];
    }
}
