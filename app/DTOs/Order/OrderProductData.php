<?php

namespace App\DTOs\Order;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class OrderProductData extends Data
{
    public function __construct(
        #[MapInputName('id')]
        public readonly int $product_id,
        public readonly float $quantity = 1,
    ) {
    }
}
