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
            'image' => 'nullable|image|max:10240',
            'type' => 'required|in:vendor,buyer,therapist',
            'account_statement' => 'nullable|file|max:10240|mimes:png,jpg,jpeg,pdf',
            'commercial_register' => 'nullable|file|max:10240|mimes:png,jpg,jpeg,pdf',
            'tax_card' => 'nullable|file|max:10240|mimes:png,jpg,jpeg,pdf',
            'card_image' => 'nullable|file|max:10240|mimes:png,jpg,jpeg,pdf',
            'license_document' => 'nullable|file|max:10240|mimes:png,jpg,jpeg,pdf',
            'id_document' => 'nullable|file|max:10240|mimes:png,jpg,jpeg,pdf',
        ];
    }
}
