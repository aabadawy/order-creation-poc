<?php

namespace App\DTOs\Order;

use App\Models\User;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class OrderData extends Data
{
    public function __construct
    (
        readonly public User $client,
        #[DataCollectionOf(OrderProductData::class)]
        readonly public DataCollection $products,
    )
    {}
}
