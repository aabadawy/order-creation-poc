<?php

namespace App\Commands\Order;

use App\DTOs\Order\OrderProductData;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\ValueObjects\QuantityValueObject;
use Spatie\LaravelData\DataCollection;

class AttachProductIngredientsToOrderCommand
{
    public function execute(
        Order $order,
        DataCollection $orderProducts
    ) {
        throw_unless($orderProducts->dataClass === OrderProductData::class,
            '$orderProducts rows should be instance of '.OrderProductData::class);

        $attachableProducts = Product::query()
            ->whereKey(collect($orderProducts)->pluck('product_id'))
            ->with('ingredients')
            ->get();

        $orderProducts = collect($orderProducts)->keyBy('product_id');

        $orderProductIngredients = $attachableProducts->map(function (Product $product) use ($orderProducts) {
            return $product->ingredients->map(function (Ingredient $ingredient) use ($product, $orderProducts) {

                $ingredientSubtractedQuantity = $orderProducts[$product->getKey()]['quantity'] * $ingredient->pivot->quantity->toGrams();

                $orderProductIngredient = [
                    'product_id' => $product->getKey(),
                    'ingredient_id' => $ingredient->getKey(),
                    'quantity' => new QuantityValueObject($ingredientSubtractedQuantity),
                ];

                $ingredient->subtractQuantity($ingredientSubtractedQuantity);

                $ingredient->save();

                return $orderProductIngredient;
            });
        })
            ->flatten(1)
            ->keyBy('ingredient_id')
            ->toArray();

        $order->ingredients()->attach($orderProductIngredients);
    }
}
