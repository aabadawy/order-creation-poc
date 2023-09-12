<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderProductData;
use App\Models\Order;
use Spatie\LaravelData\DataCollection;

class AttachProductsToOrderCommand
{
    public function __construct(
        private AttachProductIngredientsToOrderCommand $attachProductIngredientsToOrderCommand,
    ) {
    }

    public function execute(Order $order, DataCollection $orderProductsData): void
    {
        $this->validateOrderProductsData($orderProductsData);

        $orderProducts = collect($orderProductsData)->keyBy('product_id');

        $order->products()->attach($orderProducts);

        $this->attachProductIngredientsToOrderCommand->execute($order,
            new DataCollection(OrderProductData::class, $orderProductsData));
    }

    private function validateOrderProductsData(DataCollection $orderProductsData): void
    {
        throw_unless($orderProductsData->dataClass === OrderProductData::class,
            '$orderProductsData rows should be instance of '.OrderProductData::class);
    }
}
