<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderProductData;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\ValueObjects\QuantityValueObject;

class AttachProductsToOrderCommand
{
    public function execute(Order $order, iterable $orderProductsData): void
    {
        $this->validateOrderProductsData($orderProductsData);

        $orderProducts = collect($orderProductsData)->keyBy('product_id');

        $attachableProducts = Product::query()
            ->whereKey($orderProducts->pluck('product_id'))
            ->with('ingredients')
            ->get();

        $orderProductIngredients = $attachableProducts->map(function (Product $product) use ($orderProducts) {
            return $product->ingredients->map(function (Ingredient $ingredient) use ($product, $orderProducts) {

                $usedQuantity = $orderProducts[$product->getKey()]['quantity'] * $ingredient->pivot->quantity->toGrams();

                $ingredient->subtractQuantity($usedQuantity);

                $orderProductIngredient = [
                    'product_id' => $product->getKey(),
                    'ingredient_id' => $ingredient->getKey(),
                    'quantity' => new QuantityValueObject($usedQuantity),
                ];

                $ingredient->save();

                return $orderProductIngredient;
            });
        })
            ->flatten(1)
            ->keyBy('ingredient_id')
            ->toArray();

        $order->products()->attach($orderProducts);

        $order->ingredients()->attach($orderProductIngredients);
    }

    private function validateOrderProductsData(iterable $orderProductsData): void
    {
        foreach ($orderProductsData as $orderProductData) {
            throw_unless($orderProductData instanceof OrderProductData,
                '$orderProductsData rows should be instance of '.OrderProductData::class
            );
        }
    }
}
