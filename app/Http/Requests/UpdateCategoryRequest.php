<?php

namespace App\Http\Requests;

use App\Enums\LanguagesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
        return [
            'translation_id' => 'required|exists:translations,id',
            'name' => 'required|string|unique:translations,name,'.$id,
            'description' => 'required|string',
            'image_path' => 'required|file|mimes:pdf,jpeg,jpg,png|max:6120'
        ];
    }
}
