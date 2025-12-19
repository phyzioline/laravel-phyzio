<?php
namespace App\Http\Requests\Dashboard\TearmsCondition;

use Illuminate\Foundation\Http\FormRequest;

class TearmsConditionRequest extends FormRequest
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
            'product_usage_ar'     => 'required|string|max:1000000',
            'product_usage_en'     => 'required|string|max:1000000',

            'account_security_en'  => 'required|string|max:1000000',
            'account_security_ar'  => 'required|string|max:1000000',


            'returns_refund_ar'    => 'required|string|max:1000000',
            'returns_refund_en'    => 'required|string|max:1000000',

            'payment_policy_ar'    => 'required|string|max:1000000',
            'payment_policy_en'    => 'required|string|max:1000000',

            'legal_compliance_ar'  => 'required|string|max:1000000',
            'legal_compliance_en'  => 'required|string|max:1000000',

            'contact_support_ar'   => 'required|string|max:1000000',
            'contact_support_en'   => 'required|string|max:1000000',

        ];
    }
}
