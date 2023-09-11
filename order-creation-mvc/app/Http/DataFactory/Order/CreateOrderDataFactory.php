<?php

namespace App\Http\DataFactory\Order;

use App\DTOs\Order\OrderData;
use App\Http\Requests\Order\CreateOrderRequest;

class CreateOrderDataFactory
{

    public static function fromCreateOrderRequest(CreateOrderRequest $request): OrderData
    {
        return OrderData::from([
            'client' => auth()->user(),
            'products' => $request->validated('products')
        ]);
    }
}
