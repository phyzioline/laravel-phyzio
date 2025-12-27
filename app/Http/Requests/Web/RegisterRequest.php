<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required|string|unique:users,phone',
            'country' => 'required|string|max:2', // Country code (EG, SA, etc.)
            'image' => 'nullable|image|max:10240',
            'type' => 'required|in:vendor,buyer,therapist,company',
            // Documents removed - will be uploaded in verification center
        ];
    }
}
