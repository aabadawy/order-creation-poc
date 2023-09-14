<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderData;
use App\Events\Order\OrderCreatedEvent;
use App\Models\Order;

class CreateOrderCommand
{
    public function __construct(
        private AttachProductsToOrderCommand $attachProductsToOrderCommand,
    ) {
    }

    public function execute(OrderData $data): Order
    {
        $order = (new Order([
            'client_id' => $data->client->getKey(),
        ]));

        $order->save();

        $order->refresh();

        $this->attachProductsToOrderCommand->execute($order, $data->products);

        event(new OrderCreatedEvent($order));

        return $order->load(['ingredients', 'products']);
    }
}
