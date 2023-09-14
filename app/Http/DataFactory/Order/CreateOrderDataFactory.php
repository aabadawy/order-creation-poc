<?php

namespace App\Http\DataFactory\Order;

use App\DTOs\Order\OrderData;
use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderDataFactory
{
    public function from($request):OrderData
    {
        if ($request instanceof CreateOrderRequest) {
            return $this->fromCreateOrderRequest($request);
        }

        throw new \InvalidArgumentException('the passed argument not handled!');
    }

    protected function fromCreateOrderRequest(CreateOrderRequest $request): OrderData
    {
        return OrderData::from([
            'client' => auth()->user(),
            'products' => $request->validated('products'),
        ]);
    }
}
