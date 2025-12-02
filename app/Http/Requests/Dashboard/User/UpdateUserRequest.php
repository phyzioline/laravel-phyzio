<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user),
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user),
            ],
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'account_statement' => 'nullable|file|max:2048',
            'commercial_register' => 'nullable|file|max:2048',
            'tax_card' => 'nullable|file|max:2048',
            'card_image' => 'nullable|file|max:2048',
            'role_id'         => ['nullable', 'exists:roles,id'],

        ];
    }
}
