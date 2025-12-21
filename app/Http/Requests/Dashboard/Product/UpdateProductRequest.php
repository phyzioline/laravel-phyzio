<?php

namespace App\Http\Requests\Dashboard\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'category_id'          => 'nullable|exists:categories,id',
            'sub_category_id'      => 'nullable|exists:sub_categories,id',
            'product_name_en'      => 'nullable|string|max:255',
            'product_name_ar'      => 'nullable|string|max:255',
            'product_price'        => 'nullable|numeric|min:0',
            'short_description_ar' => 'nullable|string|max:1000',
            'short_description_en' => 'nullable|string|max:1000',
            'long_description_ar'  => 'nullable|string|max:1000',
            'long_description_en'  => 'nullable|string|max:1000',
             'amount' => 'nullable|numeric|min:0',
            'images'               => 'nullable|array|min:1',
            'images.*'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags'                 => 'nullable|array|min:1',
            'tags.*'               => 'nullable|exists:tags,id',
            'status'               => 'nullable|in:active,inactive',
            // Medical Engineer Service
            'has_engineer_option'  => 'nullable|boolean',
            'engineer_price'       => 'nullable|required_if:has_engineer_option,1|numeric|min:0',
            'engineer_required'    => 'nullable|boolean',
        ];
    }
}
