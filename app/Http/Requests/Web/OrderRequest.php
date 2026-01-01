<?php
namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        $rules = [
           'payment_method' => 'required|in:card,wallet,cash',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ];
        
        // Email required for guest checkout
        if (!auth()->check()) {
            $rules['email'] = 'required|email|max:255';
        }
        
        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => __('The payment method field is required.'),
            'payment_method.in' => __('Please select a valid payment method.'),
            'name.required' => __('The name field is required.'),
            'address.required' => __('The address field is required.'),
            'phone.required' => __('The phone field is required.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('Please enter a valid email address.'),
        ];
    }
}
