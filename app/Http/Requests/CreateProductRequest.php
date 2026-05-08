<?php

namespace App\Http\Requests;

use App\Enums\LanguagesEnum;
// use App\Enums\PagesNamesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProductRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'description' => 'required|string|unique:translations,description',
            'lang' => ['required', new Enum(LanguagesEnum::class)],
            'quantity' => 'required|integer|min:0',
            'price' => 'required|decimal:0,2',
            'discount' => 'nullable|decimal:0,2',
            'category_id' => 'required|exists:categories,id',
            'shop_id' => 'required|exists:shops,id',
            // 'images' => ['required','array','max:'.count(PagesNamesEnum::cases())],
            // 'images.*' => 'required|array',
            // 'images.*.page_name' => ['required', new Enum(PagesNamesEnum::class),'distinct'],
            // 'images.*.image_path' => 'required|file|mimes:pdf,jpeg,jpg,png|max:6120'
        ];
    }
}
