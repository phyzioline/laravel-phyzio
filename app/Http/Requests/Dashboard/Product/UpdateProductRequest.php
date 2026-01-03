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
            'long_description_ar'  => 'nullable|string',
            'long_description_en'  => 'nullable|string',
            'amount'               => 'nullable|numeric|min:0',
            'images'               => 'nullable|array',
            'images.*'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_images'        => 'nullable|string',
            'tags'                 => 'nullable|array|min:1',
            'tags.*'               => 'nullable|exists:tags,id',
            'status'               => 'nullable|in:active,inactive',
            'action'               => 'nullable|in:draft,publish',
            // Medical Engineer Service
            'has_engineer_option'  => 'nullable|boolean',
            'engineer_price'       => 'nullable|required_if:has_engineer_option,1|numeric|min:0',
            'engineer_required'    => 'nullable|boolean',
            // New fields
            'brand_name'          => 'nullable|string|max:255',
            'model_number'        => 'nullable|string|max:255',
            'manufacturer'        => 'nullable|string|max:255',
            'bullet_points'       => 'nullable|string',
            'generic_keywords'    => 'nullable|string|max:500',
            'product_type'        => 'nullable|string|max:255',
            'compare_at_price'    => 'nullable|numeric|min:0',
            'cost_price'          => 'nullable|numeric|min:0',
            'min_quantity'        => 'nullable|integer|min:1',
            'max_quantity'        => 'nullable|integer|min:1',
            'track_inventory'    => 'nullable|boolean',
            'barcode'             => 'nullable|string|max:255',
            'ean'                 => 'nullable|string|max:255',
            'upc'                 => 'nullable|string|max:255',
            'has_variations'      => 'nullable|boolean',
            'variation_attributes' => 'nullable|array',
            'country_of_origin'   => 'nullable|string|max:255',
            'warranty_description' => 'nullable|string',
            'seller_warranty_description' => 'nullable|string',
            'batteries_required'  => 'nullable|boolean',
            'battery_iec_code'    => 'nullable|string|max:255',
            'dangerous_goods_regulations' => 'nullable|string|max:255',
            'item_weight'         => 'nullable|numeric|min:0',
            'item_weight_unit'    => 'nullable|string|max:50',
            'age_restriction_required' => 'nullable|boolean',
            'responsible_person_email' => 'nullable|email|max:255',
            'condition'           => 'nullable|string|in:new,used,refurbished',
            'special_features'    => 'nullable|string|max:500',
        ];
    }
}
