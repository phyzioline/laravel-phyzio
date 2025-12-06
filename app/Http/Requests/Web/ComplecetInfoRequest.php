<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class ComplecetInfoRequest extends FormRequest
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
            'phone' => 'required|string|unique:users,phone,' . auth()->id(),
            'image' => 'nullable|image|max:10240',
            'type' => 'required|in:vendor,buyer,therapist',
            'account_statement' => 'nullable|file|max:10240',
            'commercial_register' => 'nullable|file|max:10240',
            'tax_card' => 'nullable|file|max:10240',
            'card_image' => 'nullable|file|max:10240',
            'license_document' => 'nullable|file|max:10240',
            'id_document' => 'nullable|file|max:10240',
        ];
    }
}
