<?php
namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
                'phone',
                'max:255',
                Rule::unique('users')->ignore($this->user),
            ],
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image',
            'status' => 'required|in:active,inactive',
            'account_statement' => 'nullable|file',
            'commercial_register' => 'nullable|file',
            'tax_card' => 'nullable|file',
            'card_image' => 'nullable|file',
            'role_id'         => ['nullable', 'exists:roles,id'],

        ];
    }

}
