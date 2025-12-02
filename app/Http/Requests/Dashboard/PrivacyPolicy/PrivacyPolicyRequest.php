<?php

namespace App\Http\Requests\Dashboard\PrivacyPolicy;

use Illuminate\Foundation\Http\FormRequest;

class PrivacyPolicyRequest extends FormRequest
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
            'description_ar' => 'required|string|max:1000000',
            'description_en' => 'required|string|max:1000000',
        ];
    }
}
