<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'address_ar'     => 'required|string|max:255',
            'address_en'     => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'facebook'       => 'required|url|max:255',
            'twitter'        => 'required|url|max:255',
            'instagram'      => 'required|url|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
