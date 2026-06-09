<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentSendRequest extends FormRequest
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
            'name' => ['required','string','min:3'],
            'phone_number' => ['required','string','phone:AUTO'],
            'email' => ['required','email','max:255'],
            // 'amount' => ['required','decimal:0,2'],
            'currency' => ['required','string','max:3'],
            'description' => ['nullable','string','max:500']
        ];
    }

        /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'phone_number.phone' => 'Invalid Phone Number'
        ];
    }
}
