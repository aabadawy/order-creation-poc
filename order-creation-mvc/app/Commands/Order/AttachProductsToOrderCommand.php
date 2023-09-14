<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderProductData;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\ProductIngredient;
use App\ValueObjects\QuantityValueObject;
use Spatie\LaravelData\DataCollection;

class AttachProductsToOrderCommand
{
    public function __construct(
    ) {
    }

    public function execute(Order $order, DataCollection $orderProductsData): void
    {
        $this->validateOrderProductsDataInstance($orderProductsData);

        $orderProducts = collect($orderProductsData)->keyBy('product_id');

        $order->products()->attach($orderProducts);

        $orderProductIngredient = [];

        ProductIngredient::query()
            ->whereIn('product_id', $orderProducts->pluck('product_id'))
            ->lazyById(100, 'id')
            ->each(function (ProductIngredient $productIngredient) use ($orderProducts, &$orderProductIngredient) {
                $ingredientSubtractedQuantity = $orderProducts[$productIngredient->product_id]['quantity'] * $productIngredient->quantity->toGrams();

                // get ingredient instance lazy, to ensure the quantity is 'up-to-date' and no conflict happens
                $this->subtractIngredientQuantity($productIngredient->ingredient, $ingredientSubtractedQuantity);

                $orderProductIngredient[$productIngredient->ingredient->getKey()] = [
                    'product_id' => $productIngredient->product_id,
                    'quantity' => new QuantityValueObject($ingredientSubtractedQuantity),
                ];
            });

        $order->ingredients()->attach($orderProductIngredient);
    }

    protected function subtractIngredientQuantity(Ingredient $ingredient, float $usedQuantityInGrams): bool
    {
        $ingredient->subtractQuantity($usedQuantityInGrams);

        return $ingredient->save();
    }

    private function validateOrderProductsDataInstance(DataCollection $orderProductsData): void
    {
        throw_unless(
            $orderProductsData->dataClass === OrderProductData::class,
            '$orderProductsData rows should be instance of '.OrderProductData::class
        );
    }
}
