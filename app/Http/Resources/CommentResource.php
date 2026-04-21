<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="content", type="string", example="comment content"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="user", description="Loaded only if relation user is loaded", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="product", description="Loaded only if relation product is loaded", ref="#/components/schemas/ProductResource")
 * )
*/
class CommentResource extends JsonResource
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
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => $this->whenLoaded('user',fn() => new UserResource($this->user)),
            'product' => $this->whenLoaded('product',fn() => new ProductResource($this->product))
        ];
    }
}
