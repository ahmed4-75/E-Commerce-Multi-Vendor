<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="ShopResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer",example=1),
 *     @OA\Property(property="name", type="string", example="name"),
 *     @OA\Property(property="email", type="string", example="name@test.com"),
 *     @OA\Property(property="address", type="string", example="shop address"),
 *     @OA\Property(property="city", type="string", example="marg"),
 *     @OA\Property(property="state", type="string", example="cairo"),
 *     @OA\Property(property="country", type="string", example="Egypt"),
 *     @OA\Property(property="phone", type="string", nullable=true, example="+2 01065484974"),
 *     @OA\Property(property="pincode", type="string", example="shop pincode"),
 *     @OA\Property(property="website", type="string", nullable=true, example="website name"),
 *     @OA\Property(property="bank_name", type="string", example="bank name"),
 *     @OA\Property(property="bank_code", type="string", example="bank code"),
 *     @OA\Property(property="bank_address", type="string", example="bank address"),
 *     @OA\Property(property="bank_country", type="string", example="Egypt"),
 *     @OA\Property(property="account_name", type="string", example="bank account name"),
 *     @OA\Property(property="account_number", type="string", example="bank account number"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="user", description="Loaded only if relation user is loaded", ref="#/components/schemas/UserResource")
 * )
*/
class ShopResource extends JsonResource
{
    protected string $mode;

    public function __construct(mixed $resource, string $mode = 'full')
    {
        parent::__construct($resource);
        $this->mode = $mode;
    }
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
            'pincode' => $this->pincode,
            'website' => $this->website,
            'bank_name' => $this->bank_name,
            'bank_code' => $this->bank_code,
            'bank_address' => $this->bank_address,
            'bank_country' => $this->bank_country,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => $this->whenLoaded('user',fn() => new UserResource($this->user))
        ];
    }
}
