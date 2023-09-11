<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderProductData;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

class AttachProductsToOrderCommand
{

    public function execute(Order $order,iterable $orderProductsData): void
    {

        $this->validateOrderProductsData($orderProductsData);

        $orderProducts = collect($orderProductsData)->keyBy('product_id');

        $attachableProducts = Product::query()
            ->whereKey($orderProducts->pluck('product_id'))
            ->with('ingredients')
            ->get();

        $order->products()->attach($orderProducts);
    }

    private function validateOrderProductsData(iterable $orderProductsData): void
    {
        foreach ($orderProductsData as $orderProductData) {
            throw_unless($orderProductData instanceof OrderProductData,
                '$orderProductsData rows should be instance of '. OrderProductData::class
            );
        }
    }
}
