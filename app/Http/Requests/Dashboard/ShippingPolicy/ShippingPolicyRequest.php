<?php

namespace App\Http\Requests\Dashboard\ShippingPolicy;

use Illuminate\Foundation\Http\FormRequest;

class ShippingPolicyRequest extends FormRequest
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
            'description_ar' => 'required|string|max:1073741823',
            'description_en' => 'required|string|max:1073741823',
        ];
    }
}
