<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $id = $this->translation_id;
        $categoryId = (int) $this->route('id');
        return [
            'translation_id' => [
                'required',
                Rule::exists('translations', 'id')->where(function ($query) use ($categoryId) {
                    return $query
                        ->where('translationable_id', $categoryId)
                        ->where('translationable_type', Category::class);
                }),
            ],
            'name' => 'required|string|unique:translations,name,'.$id,
            'description' => 'required|string|unique:translations,description,'.$id,
            'image_path' => 'required|file|mimes:pdf,jpeg,jpg,png|max:6120'
        ];
    }
}
