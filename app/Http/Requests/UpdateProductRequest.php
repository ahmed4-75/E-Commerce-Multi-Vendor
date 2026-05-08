<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
// use App\Enums\PagesNamesEnum;
// use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
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
        $translationId = $this->translation_id;
        $productId = (int) $this->route('id');

        return [
            'translation_id' => [
                'required',
                Rule::exists('translations', 'id')->where(function ($query) use ($productId) {
                    return $query
                        ->where('translationable_id', $productId)
                        ->where('translationable_type', Product::class);
                }),
            ],
            'name' => 'required|string',
            'description' => 'required|string|unique:translations,description,'.$translationId,
            'quantity' => 'required|integer|min:0',
            'price' => 'required|decimal:0,2',
            'discount' => 'nullable|decimal:0,2',
            'category_id' => 'required|exists:categories,id',
            'shop_id' => 'required|exists:shops,id',
            // 'images' => ['required','array','max:'.count(PagesNamesEnum::cases())],
            // 'images.*' => 'required:images|array',
            // 'images.*.page_name' => ['required', new Enum(PagesNamesEnum::class),'distinct'],
            // 'images.*.image_path' => 'required:images|file|mimes:pdf,jpeg,jpg,png|max:6120',
        ];
    }
}
