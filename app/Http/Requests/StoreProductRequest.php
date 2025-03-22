<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name'                            => 'required|string',
            // 'description_en'             => 'required',
            // 'description_mm'             => 'required',
            'type'                            => 'required',
            'skus'                            => 'required|array',
            'skus.*.code'                     => 'nullable|string',
            'skus.*.stock'                    => 'required|numeric|min:1',
            // 'skus.*.stock_status'             => 'string',
            'skus.*.price'                    => 'required|numeric',
            'skus.*.sale_price'               => 'nullable|numeric|lt:skus.*.price',
            'skus.*.has_discount'             => 'nullable|boolean',
            'skus.*.discount_type'            => 'required_if:skus.*.has_discount,true',
            'skus.*.discount'                 => 'required_if:skus.*.has_discount,true|nullable|numeric',
            'skus.*.discount_schedule'        => 'nullable|boolean',
            'skus.*.discount_start_date'      => 'required_if:skus.*.discount_schedule,true|nullable|after_or_equal:today',
            'skus.*.discount_end_date'        => 'required_if:skus.*.discount_schedule,true|nullable|after_or_equal:today',
            'category'                        => 'required|array',
            'category.*'                      => 'required',
            'translates.*.additionals'        => 'array',
            'translates.*.additionals.*.text' => 'required_if:has_additional,true',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'skus.required'                          => 'You need to define more product detail.',
            'skus.*.stock.required'                  => 'The stock filed is required.',
            'skus.*.stock.numeric'                   => 'The stock filed must be numeric.',
            'skus.*.stock.min'                       => 'The stock filed must be at least 1.',
            'skus.*.discount_type.required_if'       => 'The discount price is required when a discount is applied.',
            'skus.*.discount.required_if'            => 'The discount amount is required when a discount is applied.',
            'skus.*.discount_start_date.required_if' => 'The discount start date is required when a schedule is applied.',
            'skus.*.discount_end_date.required_if'   => 'The discount end date  is required when a schedule is applied.',
            'skus.*.stock_status'                    => 'The stock status filed is required.',
            'skus.*.price.required'                  => 'The regular price field is required.',
            'skus.*.sale_price.lt'                   => 'The sale price must be less than regular price field.',
        ];
    }
}
