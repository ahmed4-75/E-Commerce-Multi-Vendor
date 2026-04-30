<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ShopBasicResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="name", type="string", example="name"),
 *     @OA\Property(property="email", type="string", example="name@test.com"),
 *     @OA\Property(property="address", type="string", example="shop address"),
 *     @OA\Property(property="city", type="string", example="marg"),
 *     @OA\Property(property="state", type="string", example="cairo"),
 *     @OA\Property(property="country", type="string", example="Egypt"),
 *     @OA\Property(property="phone", type="string", nullable=true, example="+2 01065484974")
 * )
*/
class ShopBasicResource extends JsonResource
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
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'phone' => $this->phone,
        ];
        
    }
}
