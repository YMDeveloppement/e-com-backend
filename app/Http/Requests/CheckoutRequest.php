<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => ['required', 'array', 'min:1'],

            'products.*.id' => [
                'required',
                'string',
                'exists:products,id',
            ],

            'products.*.qty' => [
                'required',
                'integer',
                // 'min:1',
            ],

            'adresse' => [
                'required',
                // 'exists:addresses,id',
            ],

            'mode_payement' => [
                'required',
                Rule::in([
                    'cash_on_delivery',
                    'stripe',
                    'paypal',
                    'masterCard',
                    'cartVisa',
                ]),
            ],

            'mode_delevery' => [
                'required',
                Rule::in([
                    'free',
                    'express',
                    'priority',
                ]),
            ],

            'discount_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'cart_data' => [
                'nullable',
                'array',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}