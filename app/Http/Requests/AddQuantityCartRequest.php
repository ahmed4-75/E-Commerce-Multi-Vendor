<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddQuantityCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = (int) $this->route('id');
        $product = Product::findOrFail($productId);
        return [
            'id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:0|max:'.$product->quantity,
        ];
    }
}
