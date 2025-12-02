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
            'email' => 'required|email|unique:users,email,id',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required|string|unique:users,phone,id',
            'image' => 'nullable|image',
            'type' => 'required|in:vendor,buyer',
 			'account_statement' => 'required_if:type,vendor|file|mimes:png,jpg,pdf',
            'commercial_register' => 'required_if:type,vendor|file|mimes:png,jpg,pdf',
            'tax_card' => 'required_if:type,vendor|file|mimes:png,jpg,pdf',
            'card_image' => 'required_if:type,vendor|file|mimes:png,jpg,pdf',
        ];
    }
}
