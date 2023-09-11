<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderData;
use App\Models\Order;

class CreateOrderCommand
{

    public function __construct(
        private AttachProductsToOrderCommand $attachProductsToOrderCommand,
    )
    {

    }

    public function execute(OrderData $data): Order
    {
        $order = (new Order([
            'client_id' => $data->client->getKey()
        ]));

        $order->save();

        $this->attachProductsToOrderCommand->execute($order,$data->products);
        // validate deduction is valid from ingredients

        // fire order created event

        // crete order

        // attach order products

        // attach order product ingredients


        return $order->refresh();
        //return created order
    }
}
