<?php

use App\Commands\Order\AttachProductsToOrderCommand;
use App\DTOs\Order\OrderProductData;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('AttachProductsToOrderCommands', function () {
    it('should have execute method', function () {
        expect(AttachProductsToOrderCommand::class)->toHaveMethod('execute');
    });

    it('should throw exception when ingredient quantity out of stock', function () {
        try {
            $quantity = new QuantityValueObject(2000);

            $productIngredientQuantity = new QuantityValueObject(100);

            $product = Product::factory()->hasAttached(
                Ingredient::factory(3)->create([
                    'init_quantity' => $quantity,
                    'current_quantity' => $quantity,
                ]), ['quantity' => $productIngredientQuantity]
            )->createOne();

            $ingredient = Ingredient::query()->first();

            $ingredient->subtractQuantity(1900);

            $ingredient->save();

            $order = Order::factory()->for(User::factory()->createOne(), 'client')->createOne();

            $orderProductsData = (new DataCollection(
                OrderProductData::class,
                [
                    OrderProductData::from(['id' => $product->getKey(), 'quantity' => 3]),
                ]
            ));

            (new AttachProductsToOrderCommand())->execute($order, $orderProductsData);
        } catch (Exception $exception) {
            expect($exception)->toBeInstanceOf(InvalidArgumentException::class);

            expect($exception->getMessage())->toEqual('value can not be minus.');
        }
    });

    it('should save order products and ingredients', function () {

        $quantity = new QuantityValueObject(2000);

        $productIngredientQuantity = new QuantityValueObject(100);

        $ingredients = Ingredient::factory(3)->create([
            'init_quantity' => $quantity,
            'current_quantity' => $quantity,
        ]);

        $product = Product::factory()
            ->hasAttached(
                $ingredients,
                ['quantity' => $productIngredientQuantity]
            )->createOne();

        $order = Order::factory()->for(User::factory()->createOne(), 'client')->createOne();

        $orderProductsData = (new DataCollection(
            OrderProductData::class,
            [
                OrderProductData::from(['id' => $product->getKey(), 'quantity' => 3]),
            ]
        ));

        (new AttachProductsToOrderCommand())->execute($order, $orderProductsData);

        $subtractedFirstIngredient = Ingredient::query()->first();

        expect($subtractedFirstIngredient->current_quantity->toGrams())
            ->toBeLessThan($ingredients->first()->current_quantity->toGrams());

        expect($subtractedFirstIngredient->current_quantity->toGrams())
            ->toEqual($ingredients->first()->current_quantity->toGrams() - ($productIngredientQuantity->toGrams() * 3));

        expect($order->products()->get())->toHaveCount(1);

        expect($order->ingredients()->get())->toHaveCount(3);

        expect($order->ingredients()->first()->pivot->quantity->toGrams())->toEqual($productIngredientQuantity->toGrams() * 3);
    });
});
