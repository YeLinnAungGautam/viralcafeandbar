<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'customer.first_name' => 'required',
            'customer.last_name'  => 'required',
            'customer.email'      => 'nullable|required_without:customer.phone|email',
            'customer.phone'      => 'nullable|required_without:customer.email',
            'customer.address'    => 'required',
            'customer.country'    => 'required',
            'items'               => 'required|array',
            'items.*.product_id'  => 'required|exists:products,id|numeric',
            'items.*.sku_id'      => 'required|exists:skus,id|numeric',
            'items.*.qty'         => 'required|numeric',
            'payment_method'      => 'required|exists:payments,id',
        ];

        return $rules;
    }
}
