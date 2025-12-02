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
           
            'phone' => 'required|string|unique:users,phone,id',
            'image' => 'nullable|image',
            'type' => 'required|in:vendor,buyer',
            'account_statement' => 'required_if:type,vendor|file',
            'commercial_register' => 'required_if:type,vendor|file',
            'tax_card' => 'required_if:type,vendor|file',
            'card_image' => 'required_if:type,vendor|file',
        ];
    }
}
