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
}
