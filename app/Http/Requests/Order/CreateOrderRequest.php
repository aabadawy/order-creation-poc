<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'products' => [
                'required', 'array', 'min:1',
            ],
            'products.*.id' => [
                'required', Rule::exists('products', 'id'),
            ],
            'products.*.quantity' => [
                'required', 'integer', 'min:1',
            ],
        ];
    }
}
